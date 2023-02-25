<?php

namespace App\Controller\Admin;

/* @TODO sanitize metadata */

use vxPHP\Http\ParameterBag;
use vxPHP\File\FilesystemFile;
use vxPHP\Image\ImageModifierFactory;
use vxPHP\Template\SimpleTemplate;
use vxPHP\Controller\Controller;
use vxPHP\Http\Response;
use vxPHP\Http\JsonResponse;
use vxPHP\Application\Application;
use vxWeb\Model\Article\Article;
use vxWeb\Model\Article\Exception\ArticleException;
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

    private const FILE_ATTRIBUTES = ['title', 'subtitle', 'description', 'customsort'];
    private const FOLDER_ATTRIBUTES = ['title', 'description'];

    /**
     * depending on route fill response with appropriate template
     *
     * (non-PHPdoc)
     * @see \vxPHP\Controller\Controller::execute()
     */
    protected function execute(): Response
    {
        return new Response();
    }

    protected function init(): JsonResponse
    {
        try {
            if(($id = $this->request->query->getInt('folder'))) {
                $folder = MetaFolder::getInstance(null, $id);
            }
            else {
                $folder = MetaFolder::getInstance(ltrim(FILES_PATH, '/'));
            }

            File::cleanupMetaFolder($folder);

            return new JsonResponse([
                'files' => $this->getFileRows($folder, $this->request->query->get('filter')),
                'folders' => $this->getFolderRows($folder),
                'breadcrumbs' => $this->getBreadcrumbs($folder),
                'currentFolder' => $folder->getId(),
                'limits' => [
                    'maxUploadSize' => min(
                        $this->toBytes(ini_get('upload_max_filesize')),
                        $this->toBytes(ini_get('post_max_size'))
                    ),
                    'maxExecutionTime' => ini_get('max_execution_time') * 900] /* 10% "safety margin" */
            ]);

        } catch (MetaFolderException $e) {
            return new JsonResponse(['error' => 1, 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

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
                    'path' => $f->getParentMetafolder() ? $f->getParentMetafolder()->getRelativePath() : '/'
                ];
            }
        }

        $files = [];

        $rows = Application::getInstance()->getVxPDO()->doPreparedQuery("SELECT filesid FROM files WHERE file LIKE ?", ['%' . $search . '%']);
        foreach(MetaFile::getInstancesByIds(array_column((array) $rows, 'filesid')) as $f) {
            $files[] = [
                'id' => $f->getId(),
                'name' => $f->getMetaFilename(),
                'path' => $f->getMetaFolder()->getRelativePath(),
                'folder' => $f->getMetaFolder()->getId(),
                'type' => $f->getFilesystemFile()->getMimetype()
            ];
        }

        return new JsonResponse(['folders' => $folders, 'files' => $files]);
    }

    protected function fileGet (): JsonResponse
    {
        $host = $this->request->getSchemeAndHttpHost();

        try {
            $mf = MetaFile::getInstance(null, $this->route->getPathParameter('id'));

            if($mf->isWebImage()) {
                $fsf = $mf->getFilesystemFile();
                $actions = ['resize 0 320'];
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
            }

            return new JsonResponse([
                'form' => array_intersect_key($mf->getData(), array_fill_keys(self::FILE_ATTRIBUTES, null)),
                'fileInfo' => [
                    'mimetype' => $mf->getMimetype(),
                    'url' => $host . '/' . $mf->getRelativePath(),
                    'name' => $mf->getFilename(),
                    'thumb' => isset($dest) ? $host . htmlspecialchars(str_replace(rtrim($this->request->server->get('DOCUMENT_ROOT'), DIRECTORY_SEPARATOR), '', $dest)) : null,
                    'cache' => $mf->getFilesystemFile()->getCacheInfo()
                ]
            ]);
        }
        catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    protected function fileUpdate (): JsonResponse
    {
        $bag = new ParameterBag(json_decode($this->request->getContent(), true));

        try {
            $file = MetaFile::getInstance(null, $this->route->getPathParameter('id'));
            $file->setMetaData(array_intersect_key($bag->all(), array_fill_keys(self::FILE_ATTRIBUTES, null)));

            return new JsonResponse(['success' => true, 'message' => 'Daten übernommen.']);
        }
        catch (\Exception $e) {
            return new JsonResponse(['error' => 1, 'message' => $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }

    protected function folderRead (): JsonResponse
    {
        $id = $this->route->getPathParameter('id');
        try {
            $folder = MetaFolder::getInstance(null, $id);
        }
        catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        File::cleanupMetaFolder($folder);

        return new JsonResponse([
            'success' => true,
            'files' => $this->getFileRows($folder, $this->request->query->get('filter')),
            'folders' => $this->getFolderRows($folder),
            'breadcrumbs' => $this->getBreadcrumbs($folder),
            'currentFolder' => ['key' => $id, 'name' => $folder->getName()]
        ]);
    }

    protected function fileMove (): JsonResponse
    {
        $bag = new ParameterBag(json_decode($this->request->getContent(), true));

        if (!($id = $bag->getInt('id')) || !$folderId = $bag->getInt('folderId')) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        try {
            $file = MetaFile::getInstance(null, $id);
            $file->move(MetaFolder::getInstance(null, $folderId));
            return new JsonResponse(['success' => true, 'message' => 'Daten übernommen.']);
        }
        catch (\Exception $e) {
            return new JsonResponse(['error' => 1, 'message' => $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }

    protected function fileDel (): JsonResponse
    {
        if(!($id = $this->route->getPathParameter('id'))) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
        try {
            MetaFile::getInstance(null, $id)->delete();
            return new JsonResponse(['success' => true]);
        }
        catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    protected function fileRename (): JsonResponse
    {
        $bag = new ParameterBag(json_decode($this->request->getContent(), true));

        if (!($id = $bag->getInt('id'))) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
        try {
            $name = trim($bag->get('name'));
            if (!$name) {
                throw new \InvalidArgumentException('Missing filename.');
            }
            $file = MetaFile::getInstance(null, $id);
            $file->rename($name);

            return new JsonResponse(['success' => true, 'name' => $file->getFilename()]);

        } catch (\Exception $e) {
            return new JsonResponse(['error' => 1, 'message' => $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }

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

            if($this->request->query->getInt('article')) {
                Article::getInstance($this->request->query->getInt('article'))->linkMetaFile($mf)->save();
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

    protected function folderUpdate (): JsonResponse
    {
        $bag = new ParameterBag(json_decode($this->request->getContent(), true));

        try {
            $mf = MetaFolder::getInstance(null, $this->route->getPathParameter('id'));
            $mf->setMetaData(array_intersect_key($bag->all(), array_fill_keys(self::FOLDER_ATTRIBUTES, null)));

            return new JsonResponse(['success' => true, 'message' => 'Daten übernommen.']);
        }
        catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'message' => $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }

    protected function folderRename (): JsonResponse
    {
        $bag = new ParameterBag(json_decode($this->request->getContent(), true));

        if (!($id = $bag->getInt('folder'))) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
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

    protected function folderAdd (): JsonResponse
    {
        $bag = new ParameterBag(json_decode($this->request->getContent(), true));

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
                'message' => 'Ordner erfolgreich erstellt.',
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

    protected function getFoldersTree (): JsonResponse
    {
        $id = $this->request->query->getInt('folder');

        if($id) {
            try {
                $currentFolder = MetaFolder::getInstance(null, $id);
            }
            catch (\Exception $e) {
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
            'success' => true, 'files' => $files, 'folders' => $folders
        ]);
    }

    protected function selectionMove (): JsonResponse
    {
        if(($destination = $this->getRequest()->query->getInt('destination')) === null) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        try {
            $destinationFolder = MetaFolder::getInstance(null, $destination);
        }
        catch (MetaFolderException $e) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        $bag = new ParameterBag(json_decode($this->getRequest()->getContent(), true));

        $fileIds = $bag->get('files', []);
        $folderIds = $bag->get('folders', []);

        if (empty($fileIds) && empty($folderIds)) {
            return new JsonResponse();
        }

        // avoid moving folders into themselves

        if (in_array($destination, $folderIds, true)) {
            return new JsonResponse(['success' => false, 'message' => 'Ordner kann nicht in sich selbst verschoben werden.']);
        }

        // do moving

        try {
            $files = MetaFile::getInstancesByIds($fileIds);
        }
        catch (MetaFileException $e) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
        try {
            $folders = MetaFolder::getInstancesByIds($folderIds);
        }
        catch (MetaFolderException $e) {
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
        return new JsonResponse(['id' => $currentFolderId, 'success' => true, 'files' => $this->getFileRows($currentFolder), 'folders' => $this->getFolderRows($currentFolder)]);
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

    private function getFileRows (MetaFolder $folder, $filter = ''): array
    {
        $route = Application::getInstance()->getCurrentRoute();

        if(in_array($route->getRouteId(), ['article_files_init', 'article_folder_read', 'article_file_upload']) && ($articleId = $this->request->query->getInt('article'))) {
            try {
                $linkedFiles = Article::getInstance($articleId)->getLinkedMetaFiles(true);
            }
            catch (ArticleException $e) {
                $linkedFiles = [];
            }
        }
        else {
            $linkedFiles = [];
        }

        $files = [];

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
                'linked' => in_array($f, $linkedFiles)
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

                $row['src'] = htmlspecialchars(str_replace(rtrim($this->request->server->get('DOCUMENT_ROOT'), DIRECTORY_SEPARATOR), '', $dest));
            }

            $files[] = $row;
        }

        return $files;
    }

    private function getFolderRows (MetaFolder $folder): array
    {
        $folders = [];

        foreach ($folder->getMetaFolders() as $f) {
            $folders[] = [
                'id' => $f->getId(),
                'name' => $f->getName()
            ];
        }

        return $folders;
    }

    private function getBreadcrumbs (MetaFolder $folder): array
    {
        $breadcrumbs = [['name' => $folder->getName(), 'folder' => $folder->getId()]];

        while (($folder = $folder->getParentMetafolder())) {
            array_unshift($breadcrumbs, ['name' => $folder->getName(), 'folder' => $folder->getId()]);
        }

        return $breadcrumbs;
    }
}