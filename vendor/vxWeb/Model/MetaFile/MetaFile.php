<?php
/*
 * This file is part of the vxPHP/vxWeb framework
 *
 * (c) Gregor Kofler <info@gregorkofler.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace vxWeb\Model\MetaFile;

use SplFileInfo;
use vxPHP\Application\Exception\ApplicationException;
use vxPHP\File\Exception\FilesystemFolderException;
use vxPHP\File\FilesystemFile;
use vxPHP\Application\Application;
use vxPHP\Observer\PublisherInterface;
use vxPHP\File\Exception\FilesystemFileException;

use vxWeb\Model\MetaFile\Exception\MetaFileException;

/**
 * mapper for metafiles
 *
 * requires database tables files, folders
 *
 * @author Gregor Kofler
 *
 * @version 1.7.0 2020-09-11
 *
 * @todo merge rename() with commit()
 * @todo allow update of createdBy user
 */
class MetaFile implements PublisherInterface
{
	/**
	 * retrieved instances accessible by their id
	 * 
	 * @var MetaFile[]
	 */
	protected static $instancesById = [];

	/**
	 * retrieved instances accessible by their path
	 *
	 * @var MetaFile[]
	 */
    protected static $instancesByPath = [];

	/**
	 * @var FilesystemFile
	 */
    protected $filesystemFile;

    /**
     * @var string
     */
    protected $mimetype;

	/**
	 * @var MetaFolder
	 */
    protected $metaFolder;

	/**
	 * @var integer
	 */
    protected $id;
	
	/**
	 * @var boolean
	 */
    protected $isObscured;

	/**
	 * @var array
	 */
    protected $data;

    /**
     * returns MetaFile instance alternatively identified by its path or its primary key in the database
     *
     * @param string|null $path
     * @param int|null $id
     * @return MetaFile
     * @throws ApplicationException
     * @throws Exception\MetaFolderException
     * @throws FilesystemFileException
     * @throws FilesystemFolderException
     * @throws MetaFileException
     */
	public static function getInstance(string $path = null, int $id = null): self
    {
		if(isset($path)) {

			$lookup = Application::getInstance()->extendToAbsoluteAssetsPath($path);

			if(!isset(self::$instancesByPath[$lookup])) {
				$mf = new self($path);
				self::$instancesByPath[$lookup] = $mf;
				self::$instancesById[$mf->getId()] = $mf;
			}
			return self::$instancesByPath[$lookup];

		}

		if(isset($id)) {

			if(!isset(self::$instancesById[$id])) {
				$mf = new self(NULL, $id);
				self::$instancesById[$id] = $mf;
				self::$instancesByPath[$mf->filesystemFile->getPath()] = $mf;
			}
			return self::$instancesById[$id];
		}

        throw new MetaFileException("Either file id or path required.");
	}

    /**
     * return array of MetaFiles identified by primary keys
     *
     * @param array $ids
     *
     * @return MetaFile[]
     * @throws ApplicationException
     * @throws Exception\MetaFolderException
     * @throws FilesystemFileException
     * @throws MetaFileException
     * @throws FilesystemFolderException
     */
	public static function getInstancesByIds(array $ids): array
    {
		$toRetrieveById = [];

		// collect ids that must be read from db

		foreach($ids as $id) {
			if(!isset(self::$instancesById[$id])) {
				$toRetrieveById[] = (int) $id;
			}
		}

		// build and execute query, if necessary

		if(count($toRetrieveById)) {

			$rows = Application::getInstance()->getDb()->doPreparedQuery('
				SELECT
					f.*,
					CONCAT(fo.path, COALESCE(f.obscured_filename, f.file)) as fullpath
				FROM
					files f
					INNER JOIN folders fo ON fo.foldersid = f.foldersid
				WHERE
					f.filesid IN (' . implode(',', array_fill(0, count($toRetrieveById), '?')) . ')',
			$toRetrieveById);

			foreach($rows as $row) {
				$mf = new self(null, null, $row);
				self::$instancesById[$mf->getId()] = $mf;
				self::$instancesByPath[$mf->filesystemFile->getPath()] = $mf;
			}
		}

		// return instances

		$metafiles = [];

		foreach($ids as $id) {
			$metafiles[] = self::$instancesById[$id];
		}

		return $metafiles;
	}

    /**
     * return MetaFiles identified by paths
     *
     * @param array $paths
     *
     * @return MetaFile[]
     * @throws ApplicationException
     * @throws Exception\MetaFolderException
     * @throws FilesystemFileException
     * @throws MetaFileException
     * @throws FilesystemFolderException
     */
	public static function getInstancesByPaths(array $paths): array
    {
		$toRetrieveByPath = [];

		// collect paths, that must be read from db

		$lookupPaths = [];

		foreach($paths as $path) {

			$lookup = Application::getInstance()->extendToAbsoluteAssetsPath($path);

			$lookupPaths[] = $lookup;

			if(!isset(self::$instancesByPath[$lookup])) {
				$pathinfo = pathinfo($lookup);

				$toRetrieveByPath[] = $pathinfo['basename'];
				$toRetrieveByPath[] = $pathinfo['dirname'] . DIRECTORY_SEPARATOR;
				$toRetrieveByPath[] = str_replace(Application::getInstance()->getAbsoluteAssetsPath(), '', $pathinfo['dirname']) . DIRECTORY_SEPARATOR;
			}
		}

		// build and execute query, if necessary

		if(count($toRetrieveByPath)) {

			$rows = Application::getInstance()->getDb()->doPreparedQuery(
				sprintf("
					SELECT
						f.*,
						CONCAT(fo.path, COALESCE(f.obscured_filename, f.file)) as fullpath
					FROM
						files f
						INNER JOIN folders fo ON fo.foldersid = f.foldersid
					WHERE
						%s
					",
					implode(' OR ', array_fill(0, count($toRetrieveByPath) / 3, 'f.file = ? AND fo.path IN (?, ?)'))
				),
				$toRetrieveByPath
			);

			foreach($rows as $row) {
				$mf = new self(null, null, $row);
				self::$instancesById[$mf->getId()] = $mf;
				self::$instancesByPath[$mf->filesystemFile->getPath()] = $mf;
			}
		}

		// return instances

		$metafiles = [];

		foreach($lookupPaths as $path) {
			$metafiles[] = self::$instancesByPath[$path];
		}

		return $metafiles;
	}

    /**
     * return all metafile instances within a certain metafolder
     * faster than Metafolder::getMetafiles()
     *
     * @param MetaFolder $folder
     * @param callable|null $callBackSort
     * @return MetaFile[]
     * @throws ApplicationException
     * @throws Exception\MetaFolderException
     * @throws FilesystemFileException
     * @throws MetaFileException
     * @throws FilesystemFolderException
     */
	public static function getMetaFilesInFolder(MetaFolder $folder, callable $callBackSort = null): array
    {
		// instance all filesystem files in folder, to speed up things

		FilesystemFile::getFilesystemFilesInFolder($folder->getFilesystemFolder());

		$result = [];

		$files = Application::getInstance()->getDb()->doPreparedQuery("SELECT f.*, CONCAT(fo.path, COALESCE(f.obscured_filename, f.file)) as fullpath FROM files f INNER JOIN folders fo ON f.foldersid = fo.foldersid WHERE fo.foldersid = ?", [(int) $folder->getId()]);

		foreach($files as &$f) {
			if(isset(self::$instancesById[$f['filesid']])) {
				$file = self::$instancesById[$f['filesid']];
			}
			else {
				$file = new self(null, null, $f);
				self::$instancesById[$f['filesid']] = $file;
				self::$instancesByPath[$file->filesystemFile->getPath()] = $file;
			}
			$result[] = $file;
		}

		if(is_null($callBackSort)) {
			return $result;
		}
		if(is_callable($callBackSort)) {
			usort($result, $callBackSort);
			return $result;
		}
		if(is_callable("self::$callBackSort")) {
			usort($result, "self::$callBackSort");
			return $result;
		}
        throw new MetaFileException(sprintf("'%s' is not callable.", $callBackSort));
	}

    /**
     * check whether $filename is already taken by a metafile in folder $f
     *
     * @param string $filename
     * @param MetaFolder $f
     * @return boolean is_available
     * @throws ApplicationException
     */
	public static function isFilenameAvailable(string $filename, MetaFolder $f): bool
    {
		// $filename is not available, if metafile with $filename is already instantiated

		if(isset(self::$instancesByPath[$f->getFullPath().$filename])) {
			return false;
		}

		// check whether $filename is found in database entries

		return count(
			Application::getInstance()->
			getDb()->
			doPreparedQuery("
				SELECT
					filesid
				FROM
					files
				WHERE
					foldersid = ? AND
					( file LIKE ? OR obscured_filename LIKE ? )
				",
				[(int) $f->getId(), (string) $filename, (string) $filename]
			)
		) === 0;
	}

    /**
     * creates a metafile instance
     * requires either id or path stored in db
     * when an array is passed to constructor
     * it sets MetaFile::data directly; used internally to avoid extra db queries
     *
     * @param string|null $path of metafile
     * @param int|null $id of metafile
     * @param array|null $dbEntry
     * @throws ApplicationException
     * @throws Exception\MetaFolderException
     * @throws FilesystemFileException
     * @throws MetaFileException
     * @throws FilesystemFolderException
     */
	private function __construct(string $path = null, int $id = null, array $dbEntry = null)
    {
		if(isset($path)) {
			$this->data = $this->getDbEntryByPath($path);
		}
		else if(isset($id)) {
			$this->data = $this->getDbEntryById($id);
		}
		else if(isset($dbEntry)) {
			$this->data = array_change_key_case($dbEntry, CASE_LOWER);
		}

		$this->id = $this->data['filesid'];
		$this->filesystemFile = FilesystemFile::getInstance(Application::getInstance()->extendToAbsoluteAssetsPath($this->data['fullpath']));
		$this->metaFolder = MetaFolder::getInstance($this->filesystemFile->getFolder()->getPath());
		$this->mimetype = $this->data['mimetype'];

		// when record features an obscured_filename, the FilesystemFile is bound to this obscured filename, while the metafile always references the non-obscured filename

		$this->isObscured = $this->data['file'] !== $this->filesystemFile->getFilename();
    }

	public function __toString()
    {
		return $this->getPath();
	}

    /**
     * retrieves file metadata stored in database
     *
     * @param string $path
     * @return array
     * @throws MetaFileException
     * @throws ApplicationException
     */
	private function getDbEntryByPath(string $path): array
    {
		$pathinfo = pathinfo($path);

		$rows = Application::getInstance()->getDb()->doPreparedQuery(
			"SELECT f.*, CONCAT(fo.path, COALESCE(f.obscured_filename, f.file)) as fullpath FROM files f INNER JOIN folders fo ON fo.foldersid = f.foldersid WHERE f.file = ? AND fo.path IN(?, ?) LIMIT 1",
			[
				$pathinfo['basename'],
				$pathinfo['dirname'] . DIRECTORY_SEPARATOR,
				str_replace(Application::getInstance()->getAbsoluteAssetsPath(), '', $pathinfo['dirname']) . DIRECTORY_SEPARATOR
			]
		);

		if($rows->count()) {
			return array_change_key_case($rows->current(), CASE_LOWER);
		}

        throw new MetaFileException(sprintf("MetaFile database entry for '%s' not found.", $path));
	}

    /**
     * retrieves file metadata stored in database
     *
     * @param int $id
     * @return array
     * @throws MetaFileException
     * @throws ApplicationException
     */
	private function getDbEntryById(int $id): array
    {
		$rows = Application::getInstance()->getDb()->doPreparedQuery(
			"SELECT f.*, CONCAT(fo.path, COALESCE(f.obscured_filename, f.file)) as fullpath FROM files f INNER JOIN folders fo ON fo.foldersid = f.foldersid WHERE f.filesid = ?",
			[(int) $id]
		);

		if($rows->count()) {
			return array_change_key_case($rows->current(), CASE_LOWER);
		}
        throw new MetaFileException(sprintf("MetaFile database entry for id '%d' not found.", $id));
	}

	/**
	 * get id of metafile
	 *
	 * @return integer
	 */
	public function getId(): int
    {
		return $this->id;
	}

    /**
     * get any data stored with metafile in database entry
     *
     * @param string|null $ndx
     * @return mixed
     */
	public function getData(string $ndx = null)
    {
		if(is_null($ndx)) {
			return $this->data;
		}
		
		$ndx = strtolower($ndx);

        return $this->data[$ndx] ?? null;
	}

	/**
	 * retrieve mime type
	 *
	 * @param bool $force forces re-read of mime type
	 * @return string
	 */
	public function getMimetype($force = false): string
    {
        if (!$this->mimetype || $force) {
            $this->mimetype = $this->filesystemFile->getMimetype($force);
        }
        return $this->mimetype;
	}

	/**
	 * check whether mime type indicates web image
	 * (i.e. image/jpeg, image/gif, image/png)
     *
	 * @param bool $force forces re-read of mime type
	 * @return boolean
	 */
	public function isWebImage($force = false): bool
    {
        if(!$this->mimetype || $force) {
            $this->mimetype = $this->filesystemFile->getMimetype($force);
        }
        return in_array($this->mimetype, FilesystemFile::WEBIMAGE_MIMETYPES, true);
	}

	/**
	 * retrieve file info
	 *
	 * @return SplFileInfo
	 */
	public function getFileInfo(): SplFileInfo
    {
		return $this->filesystemFile->getFileInfo();
	}

	/**
	 * retrieves physical path of file
	 *
	 * @return string
	 */
	public function getPath(): string
    {
		return $this->filesystemFile->getPath();
	}

    /**
     * returns path relative to assets path root
     * NULL if file is outside assets path
     *
     * @param boolean $force
     * @return string
     * @throws ApplicationException
     */
	public function getRelativePath($force = false): string
    {
		return $this->filesystemFile->getRelativePath($force);
	}

	/**
	 * retrieves physical filename of file
	 *
	 * @return string
	 */
	public function getFilename(): string
    {
		return $this->filesystemFile->getFilename();
	}

	/**
	 * retrieves metafile name of file
	 * differs from physical filename, when file is obscured
	 *
	 * @return string
	 */
	public function getMetaFilename(): string
    {
		return $this->getData('file');
	}

	/**
	 * return metafolder of metafile
	 *
	 * @return MetaFolder
	 */
	public function getMetaFolder(): MetaFolder
    {
		return $this->metaFolder;
	}

	/**
	 * returns filesystemfile of metafile
	 *
	 * @return FilesystemFile
	 */
	public function getFilesystemFile(): FilesystemFile
    {
		return $this->filesystemFile;
	}

    /**
     * rename metafile
     * both filesystem file and database entry are changed synchronously
     *
     * doesn't care about race conditions
     *
     * @param string $to new filename
     * @throws MetaFileException
     */
	public function rename(string $to): void
    {
		// obscured files only need to rename the metadata

		$oldpath = $this->filesystemFile->getPath();
		$newpath = $this->filesystemFile->getFolder()->getPath() . $to;

		if(!$this->isObscured) {
			try {
				$this->filesystemFile->rename($to);
			}
			catch(FilesystemFileException $e) {
				throw new MetaFileException(sprintf("Rename from '%s' to '%s' failed. '%s' already exists.", $oldpath, $newpath, $oldpath));
			}
		}

		try {
			Application::getInstance()->getDb()->execute('UPDATE files SET file = ? WHERE filesid = ?', array($to, $this->id));
		}

		catch(\Exception $e) {
			throw new MetaFileException(sprintf("Rename from '%s' to '%s' failed.", $oldpath, $newpath));
		}

		$this->data['file'] = $to;

		self::$instancesByPath[$newpath] = $this;
		unset(self::$instancesByPath[$oldpath]);
	}

	/**
	 * move file to a new folder
	 *
	 * @param MetaFolder $destination
	 * @throws MetaFileException
	 */
	public function move(MetaFolder $destination) {

		// nothing to do

		if($destination === $this->metaFolder) {
			return;
		}

		// move filesystem file first

		try {
			$this->filesystemFile->move($destination->getFilesystemFolder());
		}
		catch(FilesystemFileException $e) {
			throw new MetaFileException(sprintf("Moving '%s' to '%s' failed.",$this->getFilename(), $destination->getFullPath()));
		}

		// update reference in db

		try {
			Application::getInstance()->getDb()->execute('UPDATE files SET foldersid = ? WHERE filesid = ?', array($destination->getId(), $this->id));
		}
		catch(\Exception $e) {
			throw new MetaFileException(sprintf("Moving '%s' to '%s' failed.",$this->getFilename(), $destination->getFullPath()));
		}

		// update instance lookup

		unset(self::$instancesByPath[$this->getPath()]);
		$this->metaFolder = $destination;
		self::$instancesByPath[$this->getPath()] = $this;
	}

    /**
     * deletes both filesystem file and metafile and removes instance from lookup array
     * filesystem file will be kept when $keepFilesystemFile is TRUE
     *
     * @param boolean $keepFilesystemFile
     *
     * @throws FilesystemFileException
     * @throws MetaFileException
     * @throws ApplicationException
     */
	public function delete($keepFilesystemFile = false): void
    {
		MetaFileEvent::create(MetaFileEvent::BEFORE_METAFILE_DELETE, $this)->trigger();

		if(Application::getInstance()->getDb()->deleteRecord('files', $this->id)) {
			unset(self::$instancesById[$this->id]);
			unset(self::$instancesByPath[$this->filesystemFile->getPath()]);

			if(!$keepFilesystemFile) {
				$this->filesystemFile->delete();
			}
		}
		else {
			throw new MetaFileException(sprintf("Delete of metafile '%s' failed.", $this->filesystemFile->getPath()));
		}
	}

    /**
     * obscure filename
     * renames filesystem file, then updates metafile data in db and sets isObscured flag
     *
     * @param string $obscuredFilename
     * @throws FilesystemFileException
     * @throws MetaFileException
     */
	public function obscureTo(string $obscuredFilename): void
    {
		// rename filesystem file

		$this->filesystemFile->rename($obscuredFilename);

		// set metafile db attributes

		$this->setMetaData(['Obscured_Filename' => $obscuredFilename]);

		// set isObscured flag

		$this->isObscured = true;
	}

    /**
     * updates meta data of metafile
     *
     * @param array $data new data
     * @throws MetaFileException
     */
	public function setMetaData(array $data): void
    {
		$data = array_change_key_case($data, CASE_LOWER);

		/*
		 * @todo improve this hack
		 */

		if(isset($data['customsort']) && trim($data['customsort']) === '') {
			$data['customsort'] = NULL;
		}

        unset($data['file'], $data['filesid']);

        $this->data = $data + $this->data;
		$this->commit();
	}

	/**
	 * commit changes to metadata by writing data to database
	 */
	private function commit(): void
    {
		try {
			Application::getInstance()->getDb()->updateRecord('files', $this->id, $this->data);
		}
		catch (\PDOException $e) {
			throw new MetaFileException(sprintf("Data commit of file '%s' failed. PDO reports %s", $this->filesystemFile->getFilename(), $e->getMessage()));
		}
	}

    /**
     * creates a meta file based on filesystem file
     *
     * @param FilesystemFile $file
     * @return MetaFile
     * @throws ApplicationException
     * @throws Exception\MetaFolderException
     * @throws FilesystemFileException
     * @throws FilesystemFolderException
     * @throws MetaFileException
     */
	public static function createMetaFile(FilesystemFile $file): MetaFile
    {
		$db = Application::getInstance()->getDb();
	
		if(count($db->doPreparedQuery("
			SELECT
				f.filesid
			FROM
				files f
				INNER JOIN folders fo ON fo.foldersid = f.foldersid
			WHERE
				f.file = ? AND
				fo.path = ?
			LIMIT 1",
			[
				$file->getFilename(),
				$file->getFolder()->getRelativePath()
			]
		))) {
			throw new FilesystemFileException(
				sprintf(
					"Metafile '%s' in '%s' already exists as metafile.",
					$file->getFilename(),
					$file->getFolder()->getRelativePath()
				),
				FilesystemFileException::METAFILE_ALREADY_EXISTS
			);
		}
	
		$mf = MetaFolder::createMetaFolder($file->getFolder());
		$user = Application::getInstance()->getCurrentUser();
	
		if(!($filesid = $db->insertRecord('files', [
			'foldersid' => $mf->getId(),
			'file' => $file->getFilename(),
			'mimetype' => $file->getMimetype(),
			'createdby' => is_null($user) ? null : $user->getAttribute('id')
		]))) {
			throw new FilesystemFileException(sprintf("Could not create metafile for '%s'.", $file->getFilename()), FilesystemFileException::METAFILE_CREATION_FAILED);
		}

        $mfile = self::getInstance(NULL, $filesid);
        MetaFileEvent::create(MetaFileEvent::AFTER_METAFILE_CREATE, $mfile)->trigger();
        return $mfile;
	}
}