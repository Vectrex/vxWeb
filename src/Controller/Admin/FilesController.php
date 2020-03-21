<?php

namespace App\Controller\Admin;

/* @TODO sanitize metadata */

use vxPHP\Http\ParameterBag;
use vxPHP\Util\Rex;

use vxPHP\File\Exception\FilesystemFileException;
use vxPHP\File\FilesystemFile;
use vxPHP\Form\HtmlForm;
use vxPHP\Form\FormElement\FormElementFactory;
use vxPHP\Image\ImageModifierFactory;
use vxPHP\Template\SimpleTemplate;
use vxPHP\Template\Filter\ImageCache;
use vxPHP\Template\Filter\AssetsPath;
use vxPHP\Controller\Controller;
use vxPHP\Http\Response;
use vxPHP\Http\JsonResponse;
use vxPHP\Application\Application;
use vxWeb\Model\Article\Article;
use vxPHP\Constraint\Validator\RegularExpression;

use vxWeb\Model\Article\Exception\ArticleException;
use vxWeb\Model\MetaFile\MetaFile;
use vxWeb\Model\MetaFile\MetaFolder;
use vxWeb\Model\MetaFile\Exception\MetaFileException;
use vxWeb\Model\MetaFile\Exception\MetaFolderException;
use vxWeb\Util\File;

/**
 *
 * @author Gregor Kofler
 *
 */
class FilesController extends Controller
{
    private const FILE_ATTRIBUTES = ['title', 'subtitle', 'description', 'customsort'];

    /**
     * depending on route fill response with appropriate template
     *
     * (non-PHPdoc)
     * @see \vxPHP\Controller\Controller::execute()
     */
    protected function execute()
    {
        $uploadMaxFilesize = min(
            $this->toBytes(ini_get('upload_max_filesize')),
            $this->toBytes(ini_get('post_max_size'))
        );

        $maxExecutionTime = ini_get('max_execution_time');

        switch ($this->route->getRouteId()) {
            case 'filepicker':
                $tpl = 'admin/files_picker.php';
                break;

            default:
                $tpl = 'admin/files_js.php';
        }

        return new Response(
            SimpleTemplate::create($tpl)
                ->assign('upload_max_filesize', $uploadMaxFilesize)
                ->assign('max_execution_time_ms', $maxExecutionTime * 900)// 10pct "safety margin"
                ->display());
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
                'files' => $this->getFileRows($folder),
                'folders' => $this->getFolderRows($folder),
                'breadcrumbs' => $this->getBreadcrumbs($folder),
                'currentFolder' => $folder->getId()
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
        if(!($id = $this->request->query->getInt('id'))) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
        try {
            $mf = MetaFile::getInstance(null, $id);

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
                    'path' => $mf->getRelativePath(),
                    'name' => $mf->getFilename(),
                    'thumb' => $dest ? htmlspecialchars(str_replace(rtrim($this->request->server->get('DOCUMENT_ROOT'), DIRECTORY_SEPARATOR), '', $dest)) : null,
                    'cache' => $mf->getFilesystemFile()->getCacheInfo()
                ]
            ]);
        }
        catch (\Exception $e) {
            return new JsonResponse(['error' => 1, 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    protected function fileUpdate (): JsonResponse
    {
        $bag = new ParameterBag(json_decode($this->request->getContent(), true));

        if (!($id = $bag->getInt('id'))) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        try {
            $file = MetaFile::getInstance(null, $id);
            $file->setMetaData(array_intersect_key($bag->all(), array_fill_keys(self::FILE_ATTRIBUTES, null)));

            return new JsonResponse(['success' => true, 'message' => 'Daten übernommen.']);
        }
        catch (\Exception $e) {
            return new JsonResponse(['error' => 1, 'message' => $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }

    protected function folderRead (): JsonResponse
    {
        if(!($id = $this->request->query->getInt('folder'))) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        try {
            $folder = MetaFolder::getInstance(null, $id);
        }
        catch (\Exception $e) {
            return new JsonResponse(['error' => 1, 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        File::cleanupMetaFolder($folder);

        return new JsonResponse([
            'success' => true,
            'files' => $this->getFileRows($folder),
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
        if(!($id = $this->request->query->getInt('id'))) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
        try {
            MetaFile::getInstance(null, $id)->delete();
            return new JsonResponse(['success' => true]);
        }
        catch (\Exception $e) {
            return new JsonResponse(['error' => 1, 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
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
            return new JsonResponse(['error' => 1, 'message' => $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR]);
        }

        $filename = FilesystemFile::sanitizeFilename(urldecode($this->request->headers->get('x-file-name')), $fsFolder);
        $contents = file_get_contents('php://input');

        // check expected file size against real one to detect cancelled uploads

        $expectedSize = (int) $this->request->headers->get('x-file-size', 0);

        if($expectedSize !== strlen($contents)) {
            return new JsonResponse(['error' => 1, 'message' => sprintf("Submitted filesize %d doesn't match binary file size %d.", $expectedSize, strlen($contents))]);
        }

        try {
            file_put_contents($fsFolder->getPath() . $filename, $contents);
            $file = FilesystemFile::getInstance($fsFolder->getPath() . $filename);

            if(function_exists('exif_read_data') && $file->getMimetype() === 'image/jpeg') {
                $exif = exif_read_data($file->getPath());
                if (!empty($exif['Orientation'])) {
                    imagejpeg(imagerotate(imagecreatefromstring($contents), [0, 0, 0, 180, 0, 0, -90, 0, 90][$exif['Orientation']], 0), $file->getPath(), 97);
                }
            }

            $mf = MetaFile::createMetaFile($file);

            if($this->request->query->getInt('article')) {
                Article::getInstance($this->request->query->getInt('article'))->linkMetaFile($mf)->save();
            }
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 1, 'message' => sprintf("Upload von '%s' fehlgeschlagen: %s.", $filename, $e->getMessage())]);
        }

        return new JsonResponse(['success' => true, 'files' => $this->getFileRows(MetaFolder::getInstance(null, $id))]);
    }

    protected function folderDel (): JsonResponse
    {
        if(!($id = $this->request->query->getInt('folder'))) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
        try {
            MetaFolder::getInstance(null, $id)->delete();
            return new JsonResponse(['success' => true]);
        }
        catch (\Exception $e) {
            return new JsonResponse(['error' => 1, 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
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
            return new JsonResponse(['success' => true, 'folder' => [
                'id' => $folder->getId(),
                'name' => $folder->getName()
            ]]);
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

        $parseFolder = function (MetaFolder $f) use (&$parseFolder, $currentFolder) {

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
     * simple helper function to convert ini values like 10M or 256K to integer
     *
     * @param string $val
     * @return string
     */
    private function toBytes($val)
    {

        $suffix = strtolower(substr(trim($val), -1));

        $val = (int)$val;

        switch ($suffix) {
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }
        return $val;
    }

    private function renameFile()
    {

        try {
            $file = MetaFile::getInstance(NULL, $this->request->request->getInt('file'));
            $user = Application::getInstance()->getCurrentUser();

            if ($user->hasRole('superadmin') || $user->getAttribute('id') == $file->getData('createdby')) {

                $file->rename(trim($this->request->request->get('filename')));

                $metaData = $file->getData();

                return [
                    'filename' => $file->getMetaFilename(),
                    'elements' => ['html' => sprintf("<span title='%s'>%s</span>", $metaData['title'], $file->getMetaFilename())]
                ];
            }
        } catch (MetaFileException $e) {
            return ['error' => $e->getMessage()];
        }
    }

    private function delFile()
    {

        try {
            $file = MetaFile::getInstance(NULL, $this->request->request->getInt('file'));
            $user = Application::getInstance()->getCurrentUser();

            if ($user->hasRole('superadmin') || $user->getAttribute('id') == $file->getData('createdby')) {

                $folder = $file->getMetaFolder();
                $file->delete();

                return $this->getFiles($folder);
            }
        } catch (MetaFileException $e) {
            return ['error' => $e->getMessage()];
        }
    }

    private function moveFile()
    {

        try {

            $file = MetaFile::getInstance(NULL, $this->request->request->getInt('file'));
            $user = Application::getInstance()->getCurrentUser();

            if ($user->hasRole('superadmin') || $user->getAttribute('id') == $file->getData('createdby')) {

                $folder = $file->getMetafolder();
                $file->move(MetaFolder::getInstance(NULL, $this->request->request->getInt('destination')));

                return $this->getFiles($folder);
            }
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    private function addFolder(MetaFolder $folder)
    {

        $folderName = preg_replace('~[^a-z0-9_-]~i', '_', $this->request->request->get('folderName'));

        foreach ($folder->getMetaFolders() as $subFolder) {

            if ($subFolder->getName() === $folderName) {
                return ['error' => sprintf("Verzeichnis '%s' existiert bereits.", $folderName)];
            }

        }

        try {
            $folder->createFolder($folderName);
            return $this->getFiles($folder);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    private function delFolder()
    {

        try {
            if (($id = $this->request->request->getInt('del'))) {

                $folder = MetaFolder::getInstance(NULL, $id);

                if (($parent = $folder->getParentMetafolder())) {

                    $folder->delete();
                    return $this->getFiles($parent);
                }
            }
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    private function getFiles(MetaFolder $mf, array $fileColumns = NULL)
    {

        File::cleanupMetaFolder($mf);

        if (!$fileColumns) {
            $fileColumns = $this->request->request->get('fileColumns', ['name', 'size', 'mime', 'mTime']);
        }

        $folders = $this->getFolderList($mf);
        $files = $this->getFileList($mf, $fileColumns);

        $pathSegments = [['name' => $mf->getName(), 'id' => $mf->getId()]];

        while (($mf = $mf->getParentMetafolder())) {
            array_unshift($pathSegments, ['name' => $mf->getName(), 'id' => $mf->getId()]);
        }

        switch ($this->route->getRouteId()) {

            case 'filepickerXhr':
                $fileFunctions = ['rename', 'edit', 'move', 'del', 'forward'];
                break;

            default:
                $fileFunctions = ['rename', 'edit', 'move', 'del'];
        }

        return [
            'pathSegments' => $pathSegments,
            'folders' => $folders,
            'files' => $files,
            'fileFunctions' => $fileFunctions
        ];
    }

    private function getFolderList(MetaFolder $folder)
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

    private function getFileRows (MetaFolder $folder): array
    {
        $route = Application::getInstance()->getCurrentRoute();

        if(in_array($route->getRouteId(), ['article_files_init', 'article_folder_read', 'article_file_upload']) && ($articleId = $this->request->query->getInt('article'))) {
            try {
                $linkedFiles = Article::getInstance($articleId)->getLinkedMetaFiles();
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
            $metaData = $f->getData();
            $row = [
                'id' => $f->getId(),
                'name' => $f->getFilename(),
                'title' => $metaData['title'],
                'image' => $f->isWebImage(),
                'size' => $f->getFileInfo()->getSize(),
                'modified' => (new \DateTime())->setTimestamp($f->getFileInfo()->getMTime())->format('Y-m-d H:i:s'),
                'type' => $f->getMimetype(),
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

    private function getFileList(MetaFolder $folder, array $columns)
    {

        $files = [];
        $assetsPath = Application::getInstance()->getRelativeAssetsPath();

        if ($articlesId = $this->request->query->get('articlesId', $this->request->request->get('articlesId'))) {
            $linkedFiles = Article::getInstance($articlesId)->getLinkedMetaFiles();
        }

        foreach (MetaFile::getMetaFilesInFolder($folder) as $f) {

            $isImage = $f->isWebImage();
            $metaData = $f->getData();
            $file = ['columns' => [], 'id' => $f->getId(), 'filename' => $f->getMetaFilename()];

            foreach ($columns as $c) {

                switch ($c) {
                    case 'name':
                        $file['columns'][] = ['html' => sprintf('<span title="%s">%s</span>', $metaData['title'], $f->getMetaFilename())];
                        break;

                    case 'size':
                        $file['columns'][] = number_format($f->getFileInfo()->getSize(), 0, ',', '.');
                        break;

                    case 'mTime':
                        $file['columns'][] = date('Y-m-d H:i:s', $f->getFileInfo()->getMTime());
                        break;

                    case 'mime':
                        if ($isImage) {

                            // check and - if required - generate thumbnail

                            $fi = $f->getFileInfo();
                            $actions = ['crop 1', 'resize 0 40'];
                            $dest =
                                $folder->getFilesystemFolder()->createCache() .
                                "{$fi->getFilename()}@" .
                                implode('|', $actions) .
                                '.' .
                                pathinfo($fi->getFilename(), PATHINFO_EXTENSION);

                            if (!file_exists($dest)) {
                                $imgEdit = ImageModifierFactory::create($f->getFilesystemFile()->getPath());

                                foreach ($actions as $a) {
                                    $params = preg_split('~\s+~', $a);

                                    $method = array_shift($params);
                                    if (method_exists($imgEdit, $method)) {
                                        call_user_func_array([$imgEdit, $method], $params);
                                    }
                                }
                                $imgEdit->export($dest);
                            }

                            $file['columns'][] = ['html' => '<img class="thumb" src="' . htmlspecialchars(str_replace(rtrim($this->request->server->get('DOCUMENT_ROOT'), DIRECTORY_SEPARATOR), '', $dest)) . '" alt="" />'];
                        } else {
                            $file['columns'][] = $f->getMimetype();
                        }
                        break;

                    case 'linked':
                        if (isset($linkedFiles) && in_array($f, $linkedFiles, TRUE)) {
                            $file['columns'][] = ['html' => '<label class="form-switch"><input type="checkbox" class="link" checked="checked"><i class="form-icon"></i></label>'];
                            $file['linked'] = TRUE;
                        } else {
                            $file['columns'][] = ['html' => '<label class="form-switch"><input type="checkbox" class="link"><i class="form-icon"></i></label>'];
                            $file['linked'] = FALSE;
                        }
                        break;
                }
            }

            if (
                $this->route->getRouteId() === 'filepickerXhr' && (
                    is_null($this->request->query->get('filter')) ||
                    $this->request->query->get('filter') != 'image' ||
                    $isImage
                )) {
                $file['forward'] = ['filename' => '/' . $assetsPath . $f->getRelativePath(), 'ckEditorFuncNum' => (int)$this->request->query->get('CKEditorFuncNum')];
            }

            $files[] = $file;
        }

        return $files;
    }

    private function getFolderTree(MetaFolder $currentFolder = null)
    {
        $parseFolder = function (MetaFolder $f) use (&$parseFolder, $currentFolder) {

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

        return $trees;
    }

    private function renderFolderTree($tree)
    {
        $markup = '';

        $renderTree = function ($treeData) use (&$markup, &$renderTree) {

            $className = $treeData['current'] ? 'current' : '';
            $className .= empty($treeData['branches']) ? ' terminates' : '';

            $markup .= sprintf(
                '<li class="%s"><input type="checkbox" id="treeBranch_%d"><label for="treeBranch_%d"></label><span id="folder_%d">%s</span>',
                trim($className),
                $treeData['key'],
                $treeData['key'],
                $treeData['key'],
                $treeData['label']
            );

            if (!empty($treeData['branches'])) {
                $markup .= '<ul>';

                foreach ($treeData['branches'] as $branch) {
                    $markup .= $renderTree($branch);
                }

                $markup .= '</ul>';
            }

            $markup .= '</li>';

        };

        $renderTree($tree);

        return '<ul class="vx-tree">' . $markup . '</ul>';

    }

}