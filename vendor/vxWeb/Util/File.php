<?php
namespace vxWeb\Util;

use vxPHP\Model\Article\Article;
use vxPHP\File\Exception\FilesystemFileException;
use vxPHP\File\FilesystemFolder;
use vxPHP\File\FilesystemFile;
use vxPHP\File\UploadedFile;
use vxPHP\Http\Request;
use vxPHP\Application\Application;

use vxWeb\Model\MetaFile\MetaFile;
use vxWeb\Model\MetaFile\MetaFolder;
use vxWeb\Model\MetaFile\Exception\MetaFolderException;

/**
 * this class contains some static methods used in various controllers of vxWeb
 *
 * @author Gregor Kofler
 *
 */
class File {

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
			', [
				$nestingInfo['l'],
				$nestingInfo['r'],
				$nestingInfo['level'] + 1
		]);

		$db->beginTransaction();

		foreach($mFolders as $d) {
			if(!is_dir($fileRoot . $d['path'])) {

				$l = $d['l'];
				$r = $d['r'];
				$rl = $r - $l + 1;

				// delete "potential" subdirectories

				$db->execute('DELETE FROM folders WHERE l >= ? AND r <= ?', [(int) $l, (int) $r]);

				// update nesting

				$db->execute('UPDATE folders SET r = r - ? WHERE r > ?', [(int) $rl, (int) $l]);
			}
		}

		$db->commit();

		// create metafolder entries for filesystemfolders

		foreach($metaFolder->getFilesystemFolder()->getFolders() as $d) {
			MetaFolder::createMetaFolder($d);
		}

		$mFiles = $db->doPreparedQuery('SELECT filesID, IFNULL(Obscured_Filename, File) AS Filename FROM files f WHERE f.foldersID = ?', [(int) $metaFolder->getId()]);
		$existing = [];

		// delete orphaned metafile entries

		foreach($mFiles as $f) {
			if(! file_exists($metaFolder->getFullPath() . $f['filename'])) {
				$db->deleteRecord('files', $f['filesid']);
			}
			else {
				$existing[] = $f['filename'];
			}
		}

		// add new filesystem files

		$fsFiles = \vxPHP\File\Util::getDir($metaFolder->getFullPath());
		$missing = array_diff($fsFiles, $existing);

		foreach($missing as $m) {
			MetaFile::createMetaFile(FilesystemFile::getInstance($metaFolder->getFullPath() . $m));
		}

	}

	/**
	 * turns uploaded file into metafile, avoiding filename collisions
	 *
	 * @param MetaFolder $metaFolder
	 * @param UploadedFile $upload
	 * @param array metadata
	 * @param boolean $unpackArchives unpack zip|gzip files when true
	 * @throws FilesystemFileException
	 *
	 * @return array metafiles | FALSE when failure
	 */
	public static function processFileUpload(MetaFolder $metaFolder, UploadedFile $upload, array $metaData = [], $unpackArchives = FALSE) {

		$metafiles = [];

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
			$uploads = [$upload];
		}

		foreach($uploads as $upload) {
			try {
				$metaFile = MetaFile::createMetaFile($upload);
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

					$metaFile = MetaFile::createMetaFile($upload);
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
	 * all extracted files are turned into FilesystemFile instances
	 *
	 * @param UploadedFile $f
	 * @throws Exception
	 * @returns array [vxPHP\File\FilesystemFile]
	 */
	private static function handleArchive(UploadedFile $f) {

		$zip = new \ZipArchive();
		$tmpName = $f->getPath();
		$status = $zip->open($tmpName);

		if($status !== TRUE) {
			throw new \Exception("Archive file reports error: $status");
		}

		$folder	= $f->getFolder();
		$path	= $folder->getPath();
		$files = [];

		for($i = 0; $i < $zip->numFiles; ++ $i) {

			$name = $zip->getNameIndex($i);

			if(substr($name, - 1) == '/') {
				continue;
			}

			if(dirname($name)) {
				$dir = MetaFolder::createMetaFolder(dirname($name));
			}
			else {
				$dir = $folder;
			}

			$dest = FilesystemFile::sanitizeFilename(basename($name), $dir);

			copy('zip://' . $tmpName . '#' . $name, $dir->getPath() . $dest);

			$files[] = FilesystemFile::getInstance($dir->getPath() . $dest);
		}

		return $files;
	}

}