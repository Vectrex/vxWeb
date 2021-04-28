<?php
/*
 * This file is part of the vxPHP/vxWeb framework
 *
 * (c) Gregor Kofler <info@gregorkofler.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace vxWeb\Util;

use vxPHP\File\FilesystemFolder;
use vxPHP\File\Exception\FilesystemFileException;
use vxPHP\File\FilesystemFile;
use vxPHP\File\UploadedFile;
use vxPHP\Application\Application;
use vxWeb\Model\MetaFile\MetaFile;
use vxWeb\Model\MetaFile\MetaFolder;

/**
 * this class contains some static methods used in various controllers
 * of vxWeb
 *
 * @author Gregor Kofler, info@gregorkofler.com
 * 
 * @version 0.4.0, 2017-03-10
 *
 */
class File
{
    /**
     * add metafolder entries for filesystem subfolders
     * add metafile entries for filesystem files
     * remove metafile entries for missing file system files
     *
     * @param MetaFolder $metaFolder the folder which will be cleaned up
     * @return void
     * @throws FilesystemFileException
     * @throws \vxPHP\Application\Exception\ApplicationException
     * @throws \vxPHP\File\Exception\FilesystemFolderException
     * @throws \vxWeb\Model\MetaFile\Exception\MetaFileException
     * @throws \vxWeb\Model\MetaFile\Exception\MetaFolderException
     */
	public static function cleanupMetaFolder(MetaFolder $metaFolder): void
    {
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

		$mFiles = $db->doPreparedQuery('SELECT filesID, COALESCE(Obscured_Filename, File) AS Filename FROM files f WHERE f.foldersID = ?', [(int) $metaFolder->getId()]);
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

		$fsFiles = self::getFsFiles($metaFolder->getFullPath());
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
     * @return MetaFile[] | boolean false when failure
     * @throws FilesystemFileException
     * @throws \Exception
     */
	public static function processFileUpload(MetaFolder $metaFolder, UploadedFile $upload, array $metaData = [], $unpackArchives = false)
    {
		$metafiles = [];

		// check for archive

		if(preg_match('~^application/.*?(gz|zip|compressed)~', $upload->getMimeType()) && $unpackArchives) {
			try {
				$uploads = self::extractZip($upload->getPath(), $metaFolder->getFilesystemFolder());
				$upload->delete();
			}
			catch(\Exception $e) {
				return false;
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
                    $metaFile->obscureTo(bin2hex(random_bytes(16)));
				}
			}

			// error with metafile creation(e.g. duplicate names with obscured files)

			catch(FilesystemFileException $e) {

				if($e->getCode() === FilesystemFileException::METAFILE_ALREADY_EXISTS) {

					preg_match('~^(.*?)(\((\d+)\))?(.[a-z0-9]*)?$~i', $upload->getFilename(), $matches);
					$matches[2] = $matches[2] === '' ? 2 : $matches[2] + 1;

					// check for both alternative filesystem filename and metafile filename

					while(file_exists(sprintf("%s(%d)%s", $matches[1], $matches[2], $matches[4])) || ! MetaFile::isFilenameAvailable(sprintf("%s(%d)%s", $matches[1], $matches[2], $matches[4]), $metaFolder)) {
						++ $matches[2];
					}

					$upload->rename(sprintf("%s(%d)%s", $matches[1], $matches[2], $matches[4]));

					$metaFile = MetaFile::createMetaFile($upload);
					$metaFile->setMetaData($metaData);

					// obscure file if folder has the Obscure_Files attribute set

					if($metaFolder->obscuresFiles()) {
						$metaFile->obscureTo(bin2hex(random_bytes(16)));
					}
				}

				else {
					throw $e;
				}
			}

			// other upload problem

			catch(\Exception $e) {
				return false;
			}

			$metafiles[] = $metaFile;
		}

		return $metafiles;
	}

    /**
     * extract a zip archive and return the extracted
     * FilesystemFiles
     *
     * @param string $zipFilename
     * @param FilesystemFolder $folder
     * @return FilesystemFile[]
     * @throws \Exception
     */
    public static function extractZip($zipFilename, FilesystemFolder $folder)
    {
        $zip = new \ZipArchive();
        $files = [];

        if (true !== ($status = $zip->open($zipFilename))) {
            throw new \Exception(sprintf("Archive file reports error: '%s'.", $status));
        }

        for ($i = 0; $i < $zip->numFiles; ++$i) {

            $name = $zip->getNameIndex($i);

            if (substr($name, -1) === '/') {
                continue;
            }

            $dirname = dirname($name);

            if ($dirname && $dirname !== '.') {
                $dir = $folder->createFolder($dirname);
            } else {
                $dir = $folder;
            }

            $dest = FilesystemFile::sanitizeFilename(basename($name), $dir);

            copy('zip://' . $zipFilename . '#' . $name, $dir->getPath() . $dest);

            $files[] = FilesystemFile::getInstance($dir->getPath() . $dest);

        }

        $zip->close();

        return $files;
    }

    private static function getFsFiles (string $path): ?array
    {
        $dir = rtrim($path, '/') . '/';

        try {
            $i = new \DirectoryIterator($dir);
        } catch (\Exception $e) {
            return null;
        }

        $files = [];

        foreach($i as $file) {
            $fn = $file->getFileName();
            if($fn[0] !== '.' && !$file->isDot() && $file->isFile()) {
                $files[] = $fn;
            }
        }

        return $files;
    }
}