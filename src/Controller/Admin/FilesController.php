<?php

namespace App\Controller\Admin;

/* @TODO sanitize metadata */

use vxPHP\Application\Exception\ApplicationException;
use vxPHP\Application\Exception\ConfigException;
use vxPHP\File\Exception\FilesystemFileException;
use vxPHP\File\Exception\FilesystemFolderException;
use vxPHP\Http\ParameterBag;
use vxPHP\File\FilesystemFile;
use vxPHP\Image\ImageModifierFactory;
use vxPHP\Controller\Controller;
use vxPHP\Http\Response;
use vxPHP\Http\JsonResponse;
use vxPHP\Application\Application;
use vxWeb\Model\Article\Article;
use vxWeb\Model\Article\Exception\ArticleException;
use vxWeb\Model\ArticleCategory\Exception\ArticleCategoryException;
use vxWeb\Model\MetaFile\Exception\MetaFileException;
use vxWeb\Model\MetaFile\MetaFile;
use vxWeb\Model\MetaFile\MetaFolder;
use vxWeb\Model\MetaFile\Exception\MetaFolderException;
use vxWeb\Util\File;

/**
 *
 * @author Gregor Kofler
 *
 */
class FilesController extends Controller
{
    use AdminControllerTrait;

    private const array FILE_ATTRIBUTES = ['title', 'subtitle', 'description', 'customsort'];
    private const array FOLDER_ATTRIBUTES = ['title', 'description'];

    /**
     * @return JsonResponse
     * @throws \Throwable
     */
    protected function folderRead (): JsonResponse
    {
        $id = $this->route->getPathParameter('id');
        try {
            if (is_numeric($id)) {
                $folder = MetaFolder::getInstance(null, $id);
            }
            else {
                $folder = MetaFolder::getInstance(ltrim(FILES_PATH, '/'));
            }

            File::cleanupMetaFolder($folder);

            return new JsonResponse([
                'success' => true,
                'files' => $this->getFileRows($folder),
                'folders' => $this->getFolderRows($folder),
                'breadcrumbs' => $this->getBreadcrumbs($folder),
                'currentFolder' => ['key' => $folder->getId(), 'name' => $folder->getName()],
                'parentId' => $folder->getParentMetafolder()?->getId(),
                'limits' => [
                    'maxUploadSize' => min(
                        $this->toBytes(ini_get('upload_max_filesize')),
                        $this->toBytes(ini_get('post_max_size'))
                    ),
                    'maxExecutionTime' => ini_get('max_execution_time') * 900] /* 10% "safety margin" */
            ]);
        }
        catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @return JsonResponse
     * @throws MetaFileException
     * @throws MetaFolderException
     * @throws \Throwable
     * @throws ApplicationException
     * @throws ConfigException
     * @throws FilesystemFileException
     * @throws FilesystemFolderException
     */
    protected function search (): JsonResponse
    {
        $search = $this->request->query->get('search');
        if(!trim($search)) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        $allFolders = MetaFolder::instantiateAllExistingMetaFolders();

        $folders = [];

        foreach ($allFolders as $f) {
            if(mb_stripos($f->getName(), $search) !== false) {
                $folders[] = [
                    'id' => $f->getId(),
                    'name' => $f->getName(),
                    'path' => trim($f->getParentMetafolder() ? $f->getParentMetafolder()->getRelativePath() : '', '/')
                ];
            }
        }

        $files = [];

        $rows = Application::getInstance()->getVxPDO()->doPreparedQuery("SELECT filesid FROM files WHERE file LIKE ?", ['%' . $search . '%']);
        foreach(MetaFile::getInstancesByIds(array_column((array) $rows, 'filesid')) as $f) {
            $files[] = [
                'id' => $f->getId(),
                'name' => $f->getMetaFilename(),
                'type' => $f->getFilesystemFile()->getMimetype(),
                'folder' => [
                    'id' => $f->getMetaFolder()->getId(),
                    'name' => $f->getMetaFolder()->getName(),
                    'path' => trim($f->getMetaFolder()->getRelativePath(), '/')
                ],
            ];
        }

        return new JsonResponse(['folders' => $folders, 'files' => $files]);
    }

    /**
     * @return JsonResponse
     * @throws \Throwable
     */
    protected function fileGet (): JsonResponse
    {
        $host = $this->request->getSchemeAndHttpHost();

        try {
            $mf = MetaFile::getInstance(null, $this->route->getPathParameter('id'));

            if($mf->isWebImage()) {
                $fsf = $mf->getFilesystemFile();
                $actions = ['resize 480 0'];
                $dest = sprintf('%s%s@%s.%s',
                    $fsf->getFolder()->createCache(),
                    $fsf->getFilename(),
                    implode('|', $actions),
                    pathinfo($fsf->getFilename(), PATHINFO_EXTENSION)
                );

                if (!file_exists($dest)) {
                    $imgEdit = ImageModifierFactory::create($fsf->getPath());

                    foreach ($actions as $a) {
                        $params = preg_split('~\s+~', $a);

                        $method = array_shift($params);
                        if (method_exists($imgEdit, $method)) {
                            call_user_func_array([$imgEdit, $method], $params);
                        }
                    }
                    $imgEdit->export($dest);
                }
                $imageInfo = getimagesize($fsf->getPath());
                $mappedImageInfo = [];
                foreach([0 => 'w', 1 => 'h', 'bits' => 'bits', 'mime' => 'mime'] as $ndx => $key) {
                    $mappedImageInfo[$key] = $imageInfo[$ndx] ?? null;
                }
            }

            return new JsonResponse([
                'form' => array_intersect_key($mf->getData(), array_fill_keys(self::FILE_ATTRIBUTES, null)),
                'fileInfo' => [
                    'mimetype' => $mf->getMimetype(),
                    'url' => $host . '/' . $mf->getRelativePath(),
                    'name' => $mf->getFilename(),
                    'thumb' => isset($dest) ? $host . htmlspecialchars(str_replace(rtrim($this->request->server->get('DOCUMENT_ROOT'), DIRECTORY_SEPARATOR), '', $dest)) : null,
                    'cache' => $mf->getFilesystemFile()->getCacheInfo(),
                    'imageInfo' => $mappedImageInfo ?? null
                ]
            ]);
        }
        catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @return JsonResponse
     * @throws \JsonException
     * @throws \Throwable
     */
    protected function fileUpdate (): JsonResponse
    {
        $bag = new ParameterBag(json_decode($this->request->getContent(), true, 512, JSON_THROW_ON_ERROR));

        try {
            $file = MetaFile::getInstance(null, $this->route->getPathParameter('id'));
            $file->setMetaData(array_intersect_key($bag->all(), array_fill_keys(self::FILE_ATTRIBUTES, null)));

            return new JsonResponse(['success' => true, 'message' => 'Daten übernommen.']);
        }
        catch (\Exception $e) {
            return new JsonResponse(['error' => 1, 'message' => $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }

    /**
     * @return JsonResponse
     * @throws \JsonException
     * @throws \Throwable
     */
    protected function fileMove (): JsonResponse
    {
        $bag = new ParameterBag(json_decode($this->request->getContent(), true, 512, JSON_THROW_ON_ERROR));

        if (!($folderId = $bag->getInt('folderId'))) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        try {
            $file = MetaFile::getInstance(null, $this->route->getPathParameter('id'));
            $file->move(MetaFolder::getInstance(null, $folderId));
            return new JsonResponse(['success' => true, 'message' => 'Daten übernommen.']);
        }
        catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'message' => $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }

    /**
     * @return JsonResponse
     * @throws \Throwable
     */
    protected function fileDel (): JsonResponse
    {
        if(!($id = $this->route->getPathParameter('id'))) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
        try {
            MetaFile::getInstance(null, $id)->delete();
            return new JsonResponse(['success' => true, 'message' => 'Datei erfolgreich gelöscht.']);
        }
        catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @return JsonResponse
     * @throws \JsonException
     * @throws \Throwable
     */
    protected function fileRename (): JsonResponse
    {
        $bag = new ParameterBag(json_decode($this->request->getContent(), true, 512, JSON_THROW_ON_ERROR));

        try {
            $name = trim($bag->get('name'));
            if (!$name) {
                throw new \InvalidArgumentException('Missing filename.');
            }
            $file = MetaFile::getInstance(null, $this->route->getPathParameter('id'));
            $file->rename($name);

            return new JsonResponse(['success' => true, 'name' => $file->getFilename()]);

        } catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'message' => $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }

    /**
     * @return JsonResponse
     * @throws ApplicationException
     * @throws FilesystemFolderException
     * @throws MetaFolderException
     * @throws \Throwable
     */
    protected function fileUpload (): JsonResponse
    {
        $id = $this->request->query->get('folder');

        if(!$id) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        try {
            $fsFolder = MetaFolder::getInstance(null, $id)->getFilesystemFolder();
        } catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'message' => $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR]);
        }

        $filename = FilesystemFile::sanitizeFilename(urldecode($this->request->headers->get('x-file-name')), $fsFolder);
        $contents = file_get_contents('php://input');

        // check expected file size against real one to detect cancelled uploads

        $expectedSize = (int) $this->request->headers->get('x-file-size', 0);

        if($expectedSize !== strlen($contents)) {
            return new JsonResponse([
                'success' => false,
                'message' => sprintf("Mitgeteilte Dateigröße %d stimmt nicht mit jener der Datei überein (%d).", $expectedSize, strlen($contents)),
            ]);
        }

        // check content for possibly malicious PHP

        if ((strtolower(pathinfo($filename)['extension']) === 'php') && $this->checkForPHP($contents)) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Datei enthält möglicherweise bösartigen ausführbaren PHP Code.',
            ]);
        }

        try {
            file_put_contents($fsFolder->getPath() . $filename, $contents);
            $file = FilesystemFile::getInstance($fsFolder->getPath() . $filename);

            if(function_exists('exif_read_data') && $file->getMimetype() === 'image/jpeg') {
                $exif = @exif_read_data($file->getPath());
                if (!empty($exif['Orientation'])) {
                    imagejpeg(imagerotate(imagecreatefromstring($contents), [0, 0, 0, 180, 0, 0, -90, 0, 90][$exif['Orientation']], 0), $file->getPath(), 97);
                }
            }

            $mf = MetaFile::createMetaFile($file);

            if($articleId = $this->request->query->getInt('articleId')) {
                Article::getInstance($articleId)->linkMetaFile($mf)->save();
            }
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => sprintf("Upload von '%s' fehlgeschlagen: %s.", $filename, $e->getMessage()),
            ]);
        }

        return new JsonResponse([
            'success' => true,
            'message' => 'Upload erfolgreich.',
            'files' => $this->getFileRows(MetaFolder::getInstance(null, $id))
        ]);
    }

    /**
     * @return JsonResponse
     * @throws \Throwable
     */
    protected function folderDel (): JsonResponse
    {
        try {
            MetaFolder::getInstance(null, $this->route->getPathParameter('id'))->delete();
            return new JsonResponse(['success' => true, 'message' => 'Verzeichnis erfolgreich gelöscht.']);
        }
        catch (\Exception $e) {
            return new JsonResponse(['error' => 1, 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @return JsonResponse
     * @throws \Throwable
     */
    protected function folderGet (): JsonResponse
    {
        try {
            $mf = MetaFolder::getInstance(null, $this->route->getPathParameter('id'));

            return new JsonResponse([
                ...array_intersect_key($mf->getData(), array_fill_keys(self::FOLDER_ATTRIBUTES, null)),
                'path' => $mf->getRelativePath(),
            ]);
        }
        catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @return JsonResponse
     * @throws \JsonException
     * @throws \Throwable
     */
    protected function folderUpdate (): JsonResponse
    {
        $bag = new ParameterBag(json_decode($this->request->getContent(), true, 512, JSON_THROW_ON_ERROR));

        try {
            $mf = MetaFolder::getInstance(null, $this->route->getPathParameter('id'));
            $mf->setMetaData(array_intersect_key($bag->all(), array_fill_keys(self::FOLDER_ATTRIBUTES, null)));

            return new JsonResponse(['success' => true, 'message' => 'Daten übernommen.']);
        }
        catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'message' => $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }

    /**
     * @return JsonResponse
     * @throws \JsonException
     * @throws \Throwable
     */
    protected function folderRename (): JsonResponse
    {
        $bag = new ParameterBag(json_decode($this->request->getContent(), true, 512, JSON_THROW_ON_ERROR));
        $id = $this->route->getPathParameter('id');

        try {
            $name = trim($bag->get('name'));
            if (!$name) {
                throw new \InvalidArgumentException('Missing folder name.');
            }
            $folder = MetaFolder::getInstance(null, $id);

            $folder->rename($name);

            return new JsonResponse(['success' => true, 'name' => MetaFolder::getInstance(null, $id)->getName()]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 1, 'message' => $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }

    /**
     * @return JsonResponse
     * @throws \JsonException
     * @throws \Throwable
     */
    protected function folderAdd (): JsonResponse
    {
        $bag = new ParameterBag(json_decode($this->request->getContent(), true, 512, JSON_THROW_ON_ERROR));

        if(!($parent = $bag->getInt('parent'))) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
        try {
            $name = trim($bag->get('name'));
            if(!$name) {
                throw new \InvalidArgumentException('Missing filename.');
            }
            $name = preg_replace('~[^a-z0-9_-]~i', '_', $name);
            $parentFolder = MetaFolder::getInstance(null, $parent);

            foreach ($parentFolder->getMetaFolders() as $subFolder) {
                if ($subFolder->getName() === $name) {
                    return new JsonResponse(['error' => 1, 'message' => sprintf("Verzeichnis '%s' existiert bereits.", $name)]);
                }
            }
            $folder = $parentFolder->createFolder($name);
            return new JsonResponse([
                'success' => true,
                'message' => 'Verzeichnis erfolgreich erstellt.',
                'folder' => [
                    'id' => $folder->getId(),
                    'name' => $folder->getName()
                ]
            ]);
        }
        catch(\Exception $e) {
            return new JsonResponse(['error' => 1, 'message' => $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }

    /**
     * @return JsonResponse
     * @throws ApplicationException
     * @throws ConfigException
     * @throws FilesystemFolderException
     * @throws MetaFolderException
     * @throws \Throwable
     */
    protected function getFoldersTree (): JsonResponse
    {
        $id = $this->request->query->getInt('folder');

        if($id) {
            try {
                $currentFolder = MetaFolder::getInstance(null, $id);
            }
            catch (\Exception) {
                return new JsonResponse(null, Response::HTTP_NOT_FOUND);
            }
        }
        else {
            $currentFolder = null;
        }

        $parseFolder = static function (MetaFolder $f) use (&$parseFolder, $currentFolder) {

            $subTrees = $f->getMetaFolders();

            $branches = [];

            if (count($subTrees)) {
                foreach ($subTrees as $s) {
                    $branches[] = $parseFolder($s);
                }
            }

            $pathSegs = explode(DIRECTORY_SEPARATOR, trim($f->getRelativePath(), DIRECTORY_SEPARATOR));

            return [
                'id' => $f->getId(),
                'label' => end($pathSegs),
                'branches' => $branches,
                'current' => $f === $currentFolder,
                'path' => $f->getRelativePath()
            ];
        };

        $trees = [];

        foreach (MetaFolder::getRootFolders() as $f) {
            $trees[] = $parseFolder($f);
        }

        return new JsonResponse($trees[0]);
    }

    /**
     * @return JsonResponse
     * @throws ApplicationException
     * @throws ConfigException
     * @throws FilesystemFileException
     * @throws FilesystemFolderException
     * @throws MetaFolderException
     * @throws \Throwable
     */
    protected function selectionDel (): JsonResponse
    {
        $files = $this->request->query->get('files');
        $folders = $this->request->query->get('folders');

        $files = $files ? explode(',', $files) : [];
        $folders = $folders ? explode(',', $folders) : [];

        $errors = [];
        $parent = null;

        foreach($files as $id) {
            try {
                if(!$parent) {
                    $parent = MetaFile::getInstance(null, $id)->getMetaFolder();
                }
                MetaFile::getInstance(null, $id)->delete();
            } catch (MetaFileException $e) {
                $errors[] = $e->getMessage();
            }
        }
        foreach($folders as $id) {
            try {
                if(!$parent) {
                    $parent = MetaFolder::getInstance(null, $id)->getParentMetafolder();
                }
                MetaFolder::getInstance(null, $id)->delete();
            } catch (MetaFileException $e) {
                $errors[] = $e->getMessage();
            }
        }

        if ($parent) {
            $files = $this->getFileRows($parent);
            $folders = $this->getFolderRows($parent);
        }
        if(count($errors)) {
            return new JsonResponse([
                'error' => 1, 'message' => $errors, 'files' => $files, 'folders' => $folders
            ]);
        }
        return new JsonResponse([
            'success' => true, 'files' => $files, 'folders' => $folders, 'message' => 'Dateien/Verzeichnisse erfolgreich gelöscht.'
        ]);
    }

    /**
     * @return JsonResponse
     * @throws ApplicationException
     * @throws ConfigException
     * @throws FilesystemFileException
     * @throws FilesystemFolderException
     * @throws MetaFolderException
     * @throws \JsonException
     * @throws \Throwable
     */
    protected function selectionMove (): JsonResponse
    {
        $destination = $this->route->getPathParameter('id');

        try {
            $destinationFolder = MetaFolder::getInstance(null, $destination);
        }
        catch (MetaFolderException) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        $bag = new ParameterBag(json_decode($this->getRequest()->getContent(), true, 512, JSON_THROW_ON_ERROR));

        $fileIds = $bag->get('files', []);
        $folderIds = $bag->get('folders', []);

        if (empty($fileIds) && empty($folderIds)) {
            return new JsonResponse();
        }

        // avoid moving folders into themselves

        if (in_array($destination, $folderIds, true)) {
            return new JsonResponse(['success' => false, 'message' => 'Verzeichnis kann nicht in sich selbst verschoben werden.']);
        }

        // do moving

        try {
            $files = MetaFile::getInstancesByIds($fileIds);
        }
        catch (MetaFileException) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
        try {
            $folders = MetaFolder::getInstancesByIds($folderIds);
        }
        catch (MetaFolderException) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        $currentFolderId = count($files) ? $files[0]->getMetaFolder()->getId() : $folders[0]->getParentMetafolder()->getId();

        try {
            foreach ($files as $file) {
                $file->move($destinationFolder);
            }
        }
        catch (MetaFileException $e) {
            return new JsonResponse(['success' => false, 'message' => $e->getMessage()]);
        }
        try {
            foreach ($folders as $folder) {
                $folder->move($destinationFolder);
            }
        }
        catch (MetaFolderException $e) {
            return new JsonResponse(['success' => false, 'message' => $e->getMessage()]);
        }

        $currentFolder = MetaFolder::getInstance(null, $currentFolderId);
        return new JsonResponse([
            'id' => $currentFolderId,
            'success' => true,
            'files' => $this->getFileRows($currentFolder),
            'folders' => $this->getFolderRows($currentFolder),
            'message' => 'Dateien/Verzeichnisse erfolgreich verschoben.'
        ]);
    }

    /**
     * checks whether a string contains (opening) PHP tags
     *
     * @param $string
     * @return bool
     */
    private function checkForPHP ($string): bool
    {
        return preg_match('/<\?(?:php|=)/i', $string) === 1;
    }

    /**
     * @param MetaFolder $folder
     * @return array
     * @throws ApplicationException
     * @throws ConfigException
     * @throws FilesystemFileException
     * @throws FilesystemFolderException
     * @throws MetaFileException
     * @throws MetaFolderException
     * @throws ArticleCategoryException
     */
    private function getFileRows (MetaFolder $folder): array
    {
        $filter = $this->request->query->get('filter');
        $articleId = $this->request->query->getInt('articleId');

        if($articleId) {
            try {
                $linkedFiles = Article::getInstance($articleId)->getLinkedMetaFiles(true);
            }
            catch (ArticleException) {
                $linkedFiles = [];
            }
        }
        else {
            $linkedFiles = [];
        }

        $files = [];
        $host = $this->request->getSchemeAndHttpHost();

        foreach (MetaFile::getMetaFilesInFolder($folder) as $f) {
            if ($filter === 'image' && !$f->isWebImage()) {
                continue;
            }
            $metaData = $f->getData();
            $row = [
                'id' => $f->getId(),
                'name' => $f->getFilename(),
                'title' => $metaData['title'],
                'image' => $f->isWebImage(),
                'size' => $f->getFileInfo()->getSize(),
                'modified' => (new \DateTime())->setTimestamp($f->getFileInfo()->getMTime())->format('Y-m-d H:i:s'),
                'type' => $f->getMimetype(),
                'path' => '/'. $f->getRelativePath(),
                'url' => $host . '/'. $f->getRelativePath(),
                'linked' => in_array($f, $linkedFiles, true)
            ];

            if($row['image']) {
                $fsf = $f->getFilesystemFile();
                $actions = ['crop 1', 'resize 48 0'];
                $dest = sprintf('%s%s@%s.%s',
                    $fsf->getFolder()->createCache(),
                    $fsf->getFilename(),
                    implode('|', $actions),
                    pathinfo($fsf->getFilename(), PATHINFO_EXTENSION)
                );

                if (!file_exists($dest)) {
                    $imgEdit = ImageModifierFactory::create($fsf->getPath());

                    foreach ($actions as $a) {
                        $params = preg_split('~\s+~', $a);

                        $method = array_shift($params);
                        if (method_exists($imgEdit, $method)) {
                            call_user_func_array([$imgEdit, $method], $params);
                        }
                    }
                    $imgEdit->export($dest);
                }

                $row['src'] = $host . htmlspecialchars(str_replace(rtrim($this->request->server->get('DOCUMENT_ROOT'), DIRECTORY_SEPARATOR), '', $dest));
            }

            $files[] = $row;
        }

        return $files;
    }

    /**
     * @param MetaFolder $folder
     * @return array
     * @throws ApplicationException
     * @throws ConfigException
     * @throws FilesystemFolderException
     * @throws MetaFolderException
     */
    private function getFolderRows (MetaFolder $folder): array
    {
        $folders = [];

        foreach ($folder->getMetaFolders() as $f) {
            $folders[] = [
                'id' => $f->getId(),
                'name' => $f->getName(),
                'path' => trim($f->getRelativePath(), '/')
            ];
        }

        return $folders;
    }

    /**
     * @param MetaFolder $folder
     * @return array[]
     * @throws ApplicationException
     * @throws FilesystemFolderException
     * @throws MetaFolderException
     */
    private function getBreadcrumbs (MetaFolder $folder): array
    {
        $breadcrumbs = [['id' => $folder->getId(), 'name' => $folder->getName(), 'path' => trim($folder->getRelativePath(), '/')]];

        while (($folder = $folder->getParentMetafolder())) {
            array_unshift($breadcrumbs, ['id' => $folder->getId(), 'name' => $folder->getName(), 'path' => trim($folder->getRelativePath(), '/')]);
        }

        return $breadcrumbs;
    }
}