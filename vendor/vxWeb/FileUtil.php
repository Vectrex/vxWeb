<?php
namespace vxWeb;

use vxPHP\Orm\Custom\Article;
use vxPHP\File\Exception\FilesystemFileException;
use vxPHP\File\Exception\MetaFolderException;
use vxPHP\File\MetaFile;
use vxPHP\File\FilesystemFolder;
use vxPHP\File\MetaFolder;
use vxPHP\File\FilesystemFile;
use vxPHP\Http\Request;
use vxPHP\Application\Application;

/**
 * this class contains some static methods used in various controllers of vxWeb
 *
 * @author Gregor Kofler
 *
 */
class FileUtil {

	/**
	 * @param Article $article
	 * @param array $metaData
	 * @param string $articlesDir optional name of subdirectory, that keeps files for articles
	 *
	 * @throws MetaFolderException
	 * @throws FilesystemFileException
	 *
	 * @return MetaFile
	 */
	public static function uploadFileForArticle(Article $article, array $metaData, $articlesDir = 'articles') {

		$parentFolder = FilesystemFolder::getInstance(rtrim(Application::getInstance()->getAbsoluteAssetsPath(), DIRECTORY_SEPARATOR) . FILES_PATH . $articlesDir);

		// @todo avoid collision of folder names with existing file names

		if(!is_dir($parentFolder->getPath() . $article->getCategory()->getAlias())) {
			$metaFolder = $parentFolder->createFolder($article->getCategory()->getAlias())->createMetafolder();
		}
		else {
			try {
				$metaFolder = MetaFolder::getInstance($parentFolder->getPath().$article->getCategory()->getAlias() . DIRECTORY_SEPARATOR);
			}
			catch(MetaFolderException $e) {
				$metaFolder = $parentFolder->createMetaFolder();
			}
		}

		if(($f = FilesystemFile::uploadFile('upload_file', $metaFolder->getFileSystemFolder()))) {

			try {
				$mf = $f->createMetaFile();
			}

			catch(FilesystemFileException $e) {
				if($e->getCode() == FilesystemFileException::METAFILE_ALREADY_EXISTS) {
					preg_match('~^(.*?)(\((\d+)\))?(.[a-z0-9]*)?$~i', $f->getFilename(), $matches);
					$matches [2] = $matches [2] == '' ? 2 : $matches [2] + 1;

					// check for both alternative filesystem filename and metafile filename

					while(file_exists("{$matches[1]}({$matches[2]}){$matches[4]}") || ! MetaFile::isFilenameAvailable("{$matches[1]}({$matches[2]}){$matches[4]}", $this->data ['folder'])) {
						+ $matches[2];
					}

					$f->rename("{$matches[1]}({$matches[2]}){$matches[4]}");

					$mf = $f->createMetaFile();
				}
				else {
					throw $e;
				}
			}

			$mf->setMetaData(array(
					'Description'		=> htmlspecialchars($metaData['file_description'])
			));

			if($metaFolder->obscuresFiles()) {
				$mf->obscureTo(uniqid());
			}

			return $mf;
		}

	}

	/**
	 * add metafolder entries for filesystem subfolders
	 * add metafile entries for filesystem files
	 * remove metafile entries for missing file system files
	 *
	 * @param MetaFolder $metaFolder the folder which will be cleaned up
	 * @return void
	 */
	public static function cleanupMetaFolder(MetaFolder $metaFolder) {

		$application = Application::getInstance();

		$nestingInfo = $metaFolder->getNestingInformation();

		$db = $application->getDb();
		$fileRoot = $application->getAbsoluteAssetsPath();

		$mFolders = $db->doPreparedQuery('
			SELECT foldersID, Path, l, r FROM folders f WHERE l > ? AND r < ? AND f.level = ?
			', array(
				$nestingInfo['l'],
				$nestingInfo['r'],
				$nestingInfo['level'] + 1
		));

		$db->beginTransaction();

		foreach($mFolders as $d) {
			if(!is_dir($fileRoot . $d['Path'])) {

				$l = $d['l'];
				$r = $d['r'];
				$rl = $r - $l + 1;

				// delete "potential" subdirectories

				$db->execute('DELETE FROM folders WHERE l >= ? AND r <= ?', array((int) $l, (int) $r));

				// update nesting

				$db->execute('UPDATE folders SET r = r - ? WHERE r > ?', array((int) $rl, (int) $l));
			}
		}

		$db->commit();

		// create metafolder entries for filesystemfolders

		foreach($metaFolder->getFilesystemFolder()->getFolders() as $d) {
			$d->createMetaFolder();
		}

		$mFiles = $db->doPreparedQuery('SELECT filesID, IFNULL(Obscured_Filename, File) AS Filename FROM files f WHERE f.foldersID = ?', array((int) $metaFolder->getId()));
		$existing = array();

		// delete orphaned metafile entries

		foreach($mFiles as $f) {
			if(! file_exists($metaFolder->getFullPath() . $f['Filename'])) {
				$db->deleteRecord('files', $f['filesID']);
			}
			else {
				$existing[] = $f['Filename'];
			}
		}

		// add new filesystem files

		$fsFiles = \vxPHP\File\Util::getDir($metaFolder->getFullPath());
		$missing = array_diff($fsFiles, $existing);

		foreach($missing as $m) {
			$add = FilesystemFile::getInstance($metaFolder->getFullPath() . $m);
			$add->createMetaFile();
		}
	}

	/**
	 * turns uploaded file into metafile, avoiding filename collisions
	 *
	 * @param MetaFolder $metaFolder
	 * @param FilesystemFile $upload
	 * @param array metadata
	 * @param boolean $unpackArchives unpack zip|gzip files when true
	 * @throws FilesystemFileException
	 *
	 * @return array metafiles | FALSE when failure
	 */
	public static function processFileUpload(MetaFolder $metaFolder, FilesystemFile $upload, array $metaData = array(), $unpackArchives = FALSE) {

		$metafiles = array();

		// check for archive

		if(preg_match('~^application/.*?(gz|zip|compressed)~', $upload->getMimeType()) && $unpackArchives) {
			try {
				$uploads = self::handleArchive($upload);
				$upload->delete();
			}
			catch(Exception $e) {
				return FALSE;
			}
		}

		else {
			$uploads = array($upload);
		}

		foreach($uploads as $upload) {
			try {
				$metaFile = $upload->createMetaFile();
				$metaFile->setMetaData($metaData);

				// obscure file if folder has the Obscure_Files attribute set

				if($metaFolder->obscuresFiles()) {
					$metaFile->obscureTo(uniqid());
				}
			}

			// error with metafile creation(e.g. duplicate names with obscured files)

			catch(FilesystemFileException $e) {

				if($e->getCode() == FilesystemFileException::METAFILE_ALREADY_EXISTS) {

					preg_match('~^(.*?)(\((\d+)\))?(.[a-z0-9]*)?$~i', $upload->getFilename(), $matches);
					$matches[2] = $matches[2] == '' ? 2 : $matches[2] + 1;

					// check for both alternative filesystem filename and metafile filename

					while(file_exists("{$matches[1]}({$matches[2]}){$matches[4]}") || ! MetaFile::isFilenameAvailable("{$matches[1]}({$matches[2]}){$matches[4]}", $metaFolder)) {
						++ $matches[2];
					}

					$upload->rename("{$matches[1]}({$matches[2]}){$matches[4]}");

					$metaFile = $upload->createMetaFile();
					$metaFile->setMetaData($metaData);

					// obscure file if folder has the Obscure_Files attribute set

					if($metaFolder->obscuresFiles()) {
						$metaFile->obscureTo(uniqid());
					}
				}

				else {
					throw $e;
				}
			}

			// other upload problem

			catch(Exception $e) {
				return FALSE;
			}

			$metafiles[] = $metaFile;
		}


		return $metafiles;

	}

	/**
	 * extracts an archive and flattens it
	 * all extracted files are turned into Metafile objects
	 *
	 * @param FilesystemFile $f
	 * @throws Exception
	 * @returns array unzipped_filesystemfiles
	 */
	private static function handleArchive(FilesystemFile $f) {

		$zip = new \ZipArchive();
		$status = $zip->open($f->getPath());

		if($status !== TRUE) {
			throw new \Exception("Archive file reports error: $status");
		}

		$path = $f->getFolder()->getPath();
		$metaFolder = MetaFolder::getInstance($path);
		$files = array();

		for($i = 0; $i < $zip->numFiles; ++ $i) {
			$name = $zip->getNameIndex($i);

			if(substr($name, - 1) == '/') {
				continue;
			}

			// @FIXME: Util::checkFileName() is no longer available

			$dest = Util::checkFileName(basename($name), $path);

			copy("zip://{$f->getPath()}#$name", $path.$dest);
			$files[] = FilesystemFile::getInstance($path . $dest);
		}

		return $files;
	}

}