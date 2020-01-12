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

use vxPHP\File\Exception\FilesystemFolderException;
use vxPHP\File\FilesystemFolder;
use vxPHP\Application\Application;
use vxWeb\Model\MetaFile\Exception\MetaFolderException;

/**
 * mapper for metafolder
 *
 * requires database tables files, folders
 *
 * @author Gregor Kofler
 *
 * @version 1.6.0 2020-01-12
 *
 * @todo compatibility checks on windows systems
 */
class MetaFolder {

	/**
	 * @var MetaFolder[]
	 */
	private static $instancesById = [];

	/**
	 * @var MetaFolder[]
	 */
	private static $instancesByPath = [];

	/**
	 * @var FilesystemFolder
	 */
	private $filesystemFolder;
	
	/**
	 * @var string
	 */
	private	$fullPath;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * primary key of metafolder row
	 * 
	 * @var integer
	 */
	private	$id;
	
	/**
	 * all stored additional data
	 * 
	 * @var array
	 */
	private $data;
	
	/**
	 * data required for nesting
	 * 
	 * @var integer $level
	 * @var integer $l
	 * @var integer $r
	 */
	private	$level, $l, $r;
	
	/**
	 * flag to indicate, that contained files should be obscured
	 * 
	 * @var boolean
	 */
	private $obscure_files;

	/**
	 * @var MetaFile[]
	 */
	private $metaFiles;

    /**
     * retrieve metafolder instance by either primary key of db entry
     * or path - both relative and absolute paths are allowed
     *
     * @param string $path
     * @param int $id
     * @return MetaFolder
     * @throws MetaFolderException
     * @throws \vxPHP\Application\Exception\ApplicationException
     */
	public static function getInstance($path = null, $id = null): MetaFolder
    {
		if(isset($path)) {
			$path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

			$lookup = Application::getInstance()->extendToAbsoluteAssetsPath($path);

			if(!isset(self::$instancesByPath[$lookup])) {
				new self($path);
			}

			return self::$instancesByPath[$lookup];
		}

		if(isset($id)) {
			if(!isset(self::$instancesById[$id])) {
				new self(null, $id);
			}
			return self::$instancesById[$id];
		}

        throw new MetaFolderException("Either folder id or path required.", MetaFolderException::ID_OR_PATH_REQUIRED);
	}

    /**
     * creates a metafolder instance
     * requires either id or path stored in db
     * when an array is passed to constructor
     * it sets MetaFolder::data directly; used internally to avoid extra db queries
     *
     * @param string $path of metafolder
     * @param integer $id of metafolder
     * @param array $dbEntry row data of a metafolder
     * @throws MetaFolderException
     * @throws \vxPHP\Application\Exception\ApplicationException
     * @throws FilesystemFolderException
     */
	private function __construct($path = null, $id = null, array $dbEntry = null)
    {
		if(isset($path)) {
			$path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
			$this->fullPath = Application::getInstance()->extendToAbsoluteAssetsPath($path);
			$this->data = $this->getDbEntryByPath($path);
		}

		else if(isset($id)) {
			$this->data = $this->getDbEntryById($id);
			$this->fullPath = substr($this->data['path'], 0, 1) === DIRECTORY_SEPARATOR ? $this->data['path'] : Application::getInstance()->getAbsoluteAssetsPath() . $this->data['path'];
		}

		else if(isset($dbEntry)) {
			$this->data = $dbEntry;
			$this->fullPath = substr($this->data['path'], 0, 1) === DIRECTORY_SEPARATOR ? $this->data['path'] : Application::getInstance()->getAbsoluteAssetsPath() . $this->data['path'];
		}

		$this->filesystemFolder = FilesystemFolder::getInstance($this->fullPath);

		$this->id = $this->data['foldersid'];
		$this->level = (int) $this->data['level'];
		$this->l = (int) $this->data['l'];
		$this->r = (int) $this->data['r'];
		$this->obscure_files = (boolean) $this->data['obscure_files'];
		$this->name = basename($this->fullPath);

        self::$instancesByPath[$this->fullPath] = $this;
        self::$instancesById[$this->id] = $this;
    }

	private function getDbEntryByPath($path) {

		if(strpos($path, Application::getInstance()->getAbsoluteAssetsPath()) === 0) {
			$altPath = trim(str_replace(Application::getInstance()->getAbsoluteAssetsPath(), '', $path), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
		}

		else {
			$altPath = $this->fullPath;
		}

		$rows = Application::getInstance()->getDb()->doPreparedQuery(
			"SELECT * FROM folders WHERE path = ? OR path = ? LIMIT 1",
			[(string) $path, (string) $altPath]
		);

		if($rows->count()) {
			return array_change_key_case($rows->current(), CASE_LOWER);
		}
		else {
			throw new MetaFolderException(sprintf("MetaFolder database entry for '%s (%s)' not found.", $this->fullPath, $path), MetaFolderException::METAFOLDER_DOES_NOT_EXIST);
		}
	}

	private function getDbEntryById($id) {

		$rows = Application::getInstance()->getDb()->doPreparedQuery(
			"SELECT * FROM folders WHERE foldersid = ? LIMIT 1",
			[(int) $id]
		);

        if($rows->count()) {
            return array_change_key_case($rows->current(), CASE_LOWER);
		}
		else {
			throw new MetaFolderException(sprintf("MetaFolder database entry for id '%d' not found.", $id), MetaFolderException::METAFOLDER_DOES_NOT_EXIST);
		}
	}

	/**
	 * refreshes nesting of instance by re-reading database entry (l, r, level)
	 */
	private function refreshNesting() {

		$rows = Application::getInstance()->getDb()->doPreparedQuery(
			"SELECT l, r, level FROM folders WHERE foldersid = ?",
			[(int) $this->id]
		);
		$this->level	= $this->data['level']	= (int) $rows[0]['level'];
		$this->l		= $this->data['l']		= (int) $rows[0]['l'];
		$this->r		= $this->data['r']		= (int) $rows[0]['r'];
	}

	/**
	 * several getters
	 */
	public function getFullPath() {
		return $this->fullPath;
	}

	public function getId() {
		return $this->id;
	}

	public function getName() {
		return $this->name;
	}
	
	/**
	 * @return array
	 */
	public function getNestingInformation() {

		return ['l' => $this->l, 'r' => $this->r, 'level' => $this->level];

	}

	/**
	 * get any data stored with metafolder in database entry
	 *
	 * @param string $ndx
	 * @return mixed
	 */
	public function getData($ndx = NULL) {
	
		if(is_null($ndx)) {
			return $this->data;
		}
	
		$ndx = strtolower($ndx);
	
		if(isset($this->data[$ndx])) {
			return $this->data[$ndx];
		}
	
	}

	/**
	 * @return FilesystemFolder
	 */
	public function getFilesystemFolder() {

		return $this->filesystemFolder;

	}

	/**
	 * @return boolean
	 */
	public function obscuresFiles() {

		return $this->obscure_files;

	}

    /**
     * returns path relative to root path of application
     * @param boolean $force
     *
     * @return string
     * @throws \vxPHP\Application\Exception\ApplicationException
     */
	public function getRelativePath($force = false): string
    {
		return $this->filesystemFolder->getRelativePath($force);
	}

    /**
     * return all metafiles within this folder
     *
     * @param boolean $force forces re-reading of metafolder
     *
     * @return MetaFile[]
     * @throws Exception\MetaFileException
     * @throws \vxPHP\Application\Exception\ApplicationException
     * @throws MetaFolderException
     */
	public function getMetaFiles($force = false): array
    {
		if(!isset($this->metaFiles) || $force) {
			$this->metaFiles = [];

			foreach(
				Application::getInstance()->getDb()->doPreparedQuery(
					'SELECT filesID FROM files WHERE foldersid = ?',
					[(int) $this->id]
				)
			as $f) {
				$this->metaFiles[] = MetaFile::getInstance(NULL, $f['filesid']);
			}
		}

		return $this->metaFiles;
	}

    /**
     * return all metafiles with a "compatible" image mimetype within
     * this folder
     *
     * @param bool $force
     * @return MetaFile[]
     * @throws Exception\MetaFileException
     * @throws \vxPHP\Application\Exception\ApplicationException
     * @throws MetaFolderException
     */
	public function getMetaImages($force = false): array
    {
        $files = $this->getMetaFiles($force);
        array_filter(
            $files,
            static function($file) {
                /* @var MetaFile $file */
                return $file->isWebImage();
            }
        );
        return $files;
    }

    /**
     * return all metafolders within this folder
     *
     * @return MetaFolder[]
     * @throws MetaFolderException
     * @throws \vxPHP\Application\Exception\ApplicationException
     */
	public function getMetaFolders(): array
    {
	    $metaFolders = [];

        foreach(
            Application::getInstance()->getDb()->doPreparedQuery(
                'SELECT * from folders WHERE l > ? AND r < ? AND level = ?',
                [(int) $this->l, (int) $this->r, $this->level + 1]
            )
        as $f) {
            $metaFolders[] = self::$instancesById[$f['foldersid']] ?? new self(null, null, $f);
		}

		return $metaFolders;
	}

    /**
     * return parent metafolder or NULL if already top folder
     *
     * @return MetaFolder | null
     * @throws MetaFolderException
     * @throws \vxPHP\Application\Exception\ApplicationException
     */
	public function getParentMetafolder()
    {
		if(!$this->level) {
			return null;
		}
		$pathSegs = explode(DIRECTORY_SEPARATOR, rtrim($this->getFullPath(), DIRECTORY_SEPARATOR));
		array_pop($pathSegs);

		return self::getInstance(implode(DIRECTORY_SEPARATOR, $pathSegs));

	}

    /**
     * create a new subdirectory
     * returns newly created MetaFolder object
     *
     * @param string $path
     * @return MetaFolder
     * @throws MetaFolderException
     * @throws \vxPHP\Application\Exception\ApplicationException
     * @throws \vxPHP\File\Exception\FilesystemFolderException
     */
	public function createFolder($path) {

		return self::createMetaFolder($this->filesystemFolder->createFolder($path));

	}

    /**
     * deletes metafolder
     *
     * if $keepFilesystemFiles is TRUE, only metadata entry of folder and contained files and folders is removed from database
     * otherwise filesystem files and folders will be deleted
     *
     * warning: any references to this instance still exists and will yield invalid results
     *
     * @param boolean $keepFilesystemFiles
     * @throws Exception\MetaFileException
     * @throws MetaFolderException
     * @throws \vxPHP\Application\Exception\ApplicationException
     * @throws \vxPHP\File\Exception\FilesystemFolderException
     * @throws \vxPHP\File\Exception\FilesystemFileException
     */
	public function delete($keepFilesystemFiles = false)
    {
		foreach($this->getMetaFiles() as $f) {
			$f->delete($keepFilesystemFiles);
		}

		foreach($this->getMetaFolders() as $f) {
			$f->delete($keepFilesystemFiles);
		}

		$this->refreshNesting();

		$db = Application::getInstance()->getDb();

		$db->deleteRecord('folders', $this->id);

		$db->execute('UPDATE folders SET r = r - 2 WHERE r > ?', [(int) $this->r]);
		$db->execute('UPDATE folders SET l = l - 2 WHERE l > ?', [(int) $this->r]);

        unset(self::$instancesById[$this->id], self::$instancesByPath[$this->filesystemFolder->getPath()]);

        if(!$keepFilesystemFiles) {
			$this->filesystemFolder->delete();
		}

		// refresh nesting for every already instantiated (parent and neighboring) folders

		foreach(self::$instancesById as $f) {
			$f->refreshNesting();
		}
	}

    /**
     * rename metafolder
     * both filesystem file and database entry are changed synchronously
     *
     * doesn't care about race conditions
     * warning: any references to the instance prior to renaming still exists and will yield invalid results
     *
     * @param string $to new filename
     * @return MetaFolder
     * @throws FilesystemFolderException
     * @throws MetaFolderException
     * @throws \vxPHP\Application\Exception\ApplicationException
     */
    public function rename($to): MetaFolder
    {
        $to = trim($to, '/\\');

        $oldpath = $this->filesystemFolder->getPath();
        $newpath = $this->filesystemFolder->getParentFolder()->getPath() . $to;
        $newRelpath = (($parent = $this->getParentMetafolder()) ? ($parent->getRelativePath() . $to) : $to) . DIRECTORY_SEPARATOR;

        try {
            $this->filesystemFolder->rename($to);
        }
        catch(FilesystemFolderException $e) {
            throw new MetaFolderException(sprintf("Rename from '%s' to '%s' failed.", $oldpath, $newpath));
        }

        try {
            Application::getInstance()->getDb()->execute(sprintf(
                'UPDATE folders SET %s = ?, path = ? WHERE foldersid = ?', Application::getInstance()->getDb()->quoteIdentifier('alias')),
                [
                    strtolower(preg_replace('~[\\\\/]~', '_', rtrim($newRelpath, DIRECTORY_SEPARATOR))),
                    $newRelpath,
                    $this->id
                ]
            );
        }
        catch(\Exception $e) {
            throw new MetaFolderException(sprintf("Update of metadata when renaming MetaFolder from '%s' to '%s' failed.", $oldpath, $newpath));
        }

        unset(self::$instancesByPath[$oldpath]);
        return self::getInstance($newpath);
    }

    /**
     * retrieves all currently in database stored metafolders
     * main purpose is reduction of db queries
     *
     * @param boolean $force forces re-reading of metafolders
     * @throws MetaFolderException
     * @throws \vxPHP\Application\Exception\ApplicationException
     * @throws FilesystemFolderException
     */
	public static function instantiateAllExistingMetaFolders($force = false)
    {
		foreach(
			Application::getInstance()->getDb()->doPreparedQuery(
				'SELECT * FROM folders', []
			)
		as $r) {
			if($force || !isset(self::$instancesById[$r['foldersid']])) {
				$r = array_change_key_case($r, CASE_LOWER);
				$f = new self(null, null, $r);

				self::$instancesByPath[$f->getFullPath()] = $f;
				self::$instancesById[$r['foldersid']] = $f;
			}
		}
	}

    /**
     * retrieve all folders with level 0
     *
     * @return MetaFolder[]
     * @throws MetaFolderException
     * @throws \vxPHP\Application\Exception\ApplicationException
     */
	public static function getRootFolders(): array
    {
		self::instantiateAllExistingMetaFolders();

		$roots = [];

		foreach(self::$instancesById as $f) {
			$nestingInfo = $f->getNestingInformation();
			if($nestingInfo['level'] === 0) {
				$roots[] = $f;
			}
		}

		if(count($roots) < 1) {
			throw new MetaFolderException('No properly defined root folders found.', MetaFolderException::NO_ROOT_FOLDER_FOUND);
		}

		return $roots;
	}

    /**
     * creates metafolder from supplied filesystem folder if not created previously
     * nested set is updated accordingly
     * returns either newly or previously created metafolder
     *
     * @param FilesystemFolder $f
     * @param array $metaData optional data for folder
     * @return MetaFolder
     * @throws MetaFolderException
     * @throws \vxPHP\Application\Exception\ApplicationException
     * @throws FilesystemFolderException
     */
	public static function createMetaFolder(FilesystemFolder $f, array $metaData = []): MetaFolder
    {
		$roots = self::getRootFolders();
		$rootFound = 0;

		foreach($roots as $root) {
			if($root->getRelativePath() === $f->getRelativePath()) {
				
				// root reached
				
				$rootFound = 1;
				
			}
		}

		if(!$rootFound && ($parentFolder = $f->getParentFolder())) {
			self::createMetaFolder($parentFolder);
		}

		try {
			self::getInstance($f->getPath());
		}

		catch(MetaFolderException $e) {

			$metaData = array_change_key_case($metaData, CASE_LOWER);

			$db = Application::getInstance()->getDb();

			if(strpos($f->getPath(), Application::getInstance()->getAbsoluteAssetsPath()) === 0) {
				$metaData['path'] = trim(substr($f->getPath(), strlen(Application::getInstance()->getAbsoluteAssetsPath())), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
			}
			else {
				$metaData['path'] = rtrim($f->getPath(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
			}

			$metaData['alias'] = strtolower(preg_replace('~[\\\\/]~', '_', rtrim($metaData['path'], DIRECTORY_SEPARATOR)));

			if(!isset($metaData['access']) || !preg_match('~^rw?$~i', $metaData['access'])) {
				$metaData['access'] = 'RW';
			}
			else {
				$metaData['access'] = strtoupper($metaData['access']);
			}

			$tree = explode(DIRECTORY_SEPARATOR, trim($metaData['path'], DIRECTORY_SEPARATOR));

			if(count($tree) === 1) {

				//no parent

				$rows = $db->doPreparedQuery('SELECT MAX(r) + 1 AS l FROM folders', []);
				$metaData['l'] = (!$rows->count() || !isset($rows->current()['l'])) ? 0 : $rows->current()['l'];
				$metaData['r'] = $rows->current()['l'] + 1;
				$metaData['level'] = 0;
			}

			else {

				array_pop($tree);

				try {
					$parent = self::getInstance(implode(DIRECTORY_SEPARATOR, $tree) . DIRECTORY_SEPARATOR);

					// has parent directory

					$rows = $db->doPreparedQuery("SELECT r, l, level FROM folders WHERE foldersID = ?", [$parent->getId()]);

					$db->execute('UPDATE folders SET r = r + 2 WHERE r >= ?', [(int) $rows->current()['r']]);
					$db->execute('UPDATE folders SET l = l + 2 WHERE l > ?', [(int) $rows->current()['r']]);

					$metaData['l'] = $rows->current()['r'];
					$metaData['r'] = $rows->current()['r'] + 1;
					$metaData['level'] = $rows->current()['level'] + 1;

				}

				catch(MetaFolderException $e) {

					// no parent directory

					$rows = $db->doPreparedQuery('SELECT MAX(r) + 1 AS l FROM folders', []);
                    $metaData['l'] = (!$rows->count() || !isset($rows->current()['l'])) ? 0 : $rows->current()['l'];
                    $metaData['r'] = $rows->current()['l'] + 1;
					$metaData['level'] = 0;

				}
			}

			$id = $db->insertRecord('folders', $metaData);

			// refresh nesting for all active metafolder instances

			foreach(array_keys(self::$instancesById) as $id) {
				self::getInstance(NULL, $id)->refreshNesting();
			}

		}

		return self::getInstance($f->getPath());
	}
}
