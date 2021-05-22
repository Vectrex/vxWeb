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

use vxPHP\Application\Exception\ApplicationException;
use vxPHP\File\Exception\FilesystemFileException;
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
 * @version 1.8.0 2020-09-15
 *
 * @todo compatibility checks on windows systems
 */
class MetaFolder
{
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
     * @param null $path
     * @param null $id
     * @return MetaFolder
     * @throws FilesystemFolderException
     * @throws MetaFolderException
     * @throws ApplicationException
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
     * return array of MetaFolders identified by primary keys
     *
     * @param array $ids
     *
     * @return MetaFolder[]
     * @throws ApplicationException
     * @throws Exception\MetaFolderException
     * @throws FilesystemFolderException
     */
    public static function getInstancesByIds(array $ids): array
    {
        $toRetrieveById = [];
        $metafolders = [];

        // collect ids that must be read from db

        foreach($ids as $id) {
            if(!isset(self::$instancesById[$id])) {
                $toRetrieveById[] = (int) $id;
            }
            else {
                $metafolders[] = self::$instancesById[$id];
            }
        }

        // build and execute query, if necessary

        if(count($toRetrieveById)) {
            $rows = Application::getInstance()->getVxPDO()->doPreparedQuery(
                'SELECT * FROM folders WHERE foldersid IN (' . implode(',', array_fill(0, count($toRetrieveById), '?')) . ')',
                $toRetrieveById
            );

            foreach($rows as $row) {
               $metafolders[] = new self(null, null, $row);
            }
        }

        return $metafolders;
    }

    /**
     * creates a metafolder instance
     * requires either id or path stored in db
     * when an array is passed to constructor
     * it sets MetaFolder::data directly; used internally to avoid extra db queries
     *
     * @param null $path of metafolder
     * @param null $id of metafolder
     * @param array|null $dbEntry row data of a metafolder
     * @throws ApplicationException
     * @throws FilesystemFolderException
     * @throws MetaFolderException
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
			$this->fullPath = strpos($this->data['path'], DIRECTORY_SEPARATOR) === 0 ? $this->data['path'] : Application::getInstance()->getAbsoluteAssetsPath() . $this->data['path'];
		}

		else if(isset($dbEntry)) {
			$this->data = $dbEntry;
			$this->fullPath = strpos($this->data['path'], DIRECTORY_SEPARATOR) === 0 ? $this->data['path'] : Application::getInstance()->getAbsoluteAssetsPath() . $this->data['path'];
		}

		$this->filesystemFolder = FilesystemFolder::getInstance($this->fullPath);

		$this->id = $this->data['foldersid'];
		$this->level = $this->data['level'];
		$this->l = $this->data['l'];
		$this->r = $this->data['r'];
		$this->obscure_files = (boolean) $this->data['obscure_files'];
		$this->name = basename($this->fullPath);

        self::$instancesByPath[$this->fullPath] = $this;
        self::$instancesById[$this->id] = $this;
    }

	private function getDbEntryByPath(string $path): array
    {
		if(strpos($path, Application::getInstance()->getAbsoluteAssetsPath()) === 0) {
			$altPath = trim(str_replace(Application::getInstance()->getAbsoluteAssetsPath(), '', $path), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
		}

		else {
			$altPath = $this->fullPath;
		}

		$rows = Application::getInstance()->getVxPDO()->doPreparedQuery(
			"SELECT * FROM folders WHERE path = ? OR path = ? LIMIT 1",
			[(string) $path, (string) $altPath]
		);

		if($rows->count()) {
			return array_change_key_case($rows->current(), CASE_LOWER);
		}
        throw new MetaFolderException(sprintf("MetaFolder database entry for '%s (%s)' not found.", $this->fullPath, $path), MetaFolderException::METAFOLDER_DOES_NOT_EXIST);
	}

	private function getDbEntryById(int $id): array
    {
		$rows = Application::getInstance()->getVxPDO()->doPreparedQuery(
			"SELECT * FROM folders WHERE foldersid = ? LIMIT 1",
			[(int) $id]
		);

        if($rows->count()) {
            return array_change_key_case($rows->current(), CASE_LOWER);
		}
        throw new MetaFolderException(sprintf("MetaFolder database entry for id '%d' not found.", $id), MetaFolderException::METAFOLDER_DOES_NOT_EXIST);
	}

	/**
	 * refreshes nesting of all cached instances by re-reading database entry (l, r, level)
	 */
	private static function refreshNestings(): void
    {
        if ($ids = array_keys (self::$instancesById)) {
            $rows = Application::getInstance()->getVxPDO()->doPreparedQuery(
                sprintf("SELECT foldersid, l, r, level FROM folders WHERE foldersid IN (%s)", implode(',', array_fill(0, count($ids), '?'))),
                $ids
            );

            foreach($rows as $row) {
                $instance = self::$instancesById[$row['foldersid']];
                $instance->level = $instance->data['level'] = $row['level'];
                $instance->r = $instance->data['r'] = $row['r'];
                $instance->l = $instance->data['l'] = $row['l'];
            }
        }
	}

	/**
	 * several getters
	 */
	public function getFullPath(): string
    {
		return $this->fullPath;
	}

	public function getId(): int
    {
		return $this->id;
	}

    public function getName(): string
    {
		return $this->name;
	}
	
	public function getNestingInformation(): array
    {
		return ['l' => $this->l, 'r' => $this->r, 'level' => $this->level];
	}

    /**
     * get any data stored with metafolder in database entry
     *
     * @param string|null $ndx
     * @return mixed
     */
	public function getData(string $ndx = NULL)
    {
        if(is_null($ndx)) {
			return $this->data;
		}
	
		$ndx = strtolower($ndx);

        return $this->data[$ndx] ?? null;
	}

	/**
	 * @return FilesystemFolder
	 */
	public function getFilesystemFolder(): FilesystemFolder
    {
		return $this->filesystemFolder;
	}

	/**
	 * @return boolean
	 */
	public function obscuresFiles(): bool
    {
		return $this->obscure_files;
	}

    /**
     * returns path relative to root path of application
     * @param boolean $force
     *
     * @return string
     * @throws ApplicationException
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
     * @throws ApplicationException
     * @throws Exception\MetaFileException
     * @throws FilesystemFileException
     * @throws FilesystemFolderException
     * @throws MetaFolderException
     */
	public function getMetaFiles($force = false): array
    {
		if(!isset($this->metaFiles) || $force) {
			$this->metaFiles = [];

			foreach(
				Application::getInstance()->getVxPDO()->doPreparedQuery(
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
     * @throws ApplicationException
     * @throws Exception\MetaFileException
     * @throws FilesystemFileException
     * @throws FilesystemFolderException
     * @throws MetaFolderException
     */
	public function getMetaImages($force = false): array
    {
        $files = $this->getMetaFiles($force);
        array_filter(
            $files,
            static function($file) {
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
     * @throws ApplicationException|FilesystemFolderException
     */
	public function getMetaFolders(): array
    {
	    $metaFolders = [];

        foreach(
            Application::getInstance()->getVxPDO()->doPreparedQuery(
                'SELECT * from folders WHERE l > ? AND r < ? AND level = ?',
                [$this->l, $this->r, $this->level + 1]
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
     * @throws ApplicationException|FilesystemFolderException
     */
	public function getParentMetafolder(): ?MetaFolder
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
     * @throws ApplicationException
     * @throws FilesystemFolderException
     */
	public function createFolder(string $path): MetaFolder
    {
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
     * @throws ApplicationException
     * @throws FilesystemFolderException
     * @throws FilesystemFileException
     */
	public function delete(bool $keepFilesystemFiles = false): void
    {
		foreach($this->getMetaFiles() as $f) {
			$f->delete($keepFilesystemFiles);
		}

		foreach($this->getMetaFolders() as $f) {
			$f->delete($keepFilesystemFiles);
		}

		$db = Application::getInstance()->getVxPDO();

		$db->beginTransaction();
		$db->deleteRecord('folders', $this->id);

		// cascading references should render this not necessary

		$db->deleteRecord('files', ['foldersid' => $this->id]);

		$db->execute('UPDATE folders SET r = r - 2 WHERE r > ?', [$this->r]);
		$db->execute('UPDATE folders SET l = l - 2 WHERE l > ?', [$this->r]);
		$db->commit();

        unset(self::$instancesById[$this->id], self::$instancesByPath[$this->filesystemFolder->getPath()]);

        if(!$keepFilesystemFiles) {
			$this->filesystemFolder->delete();
		}

		// refresh nesting for every already instantiated (parent and neighboring) folders

        self::refreshNestings();
	}

    /**
     * move metafolder
     * both filesystem folder and database entry are moved/updated synchronously
     *
     * doesn't care about race conditions
     * warning: any references to the instance prior to moving still exists and will yield invalid results
     *
     * @param MetaFolder $destination
     * @return $this
     * @throws ApplicationException
     * @throws FilesystemFolderException
     * @throws MetaFolderException
     */
	public function move (MetaFolder $destination): MetaFolder
    {
        if ($destination->l >= $this->l && $destination->r <= $this->r) {
            throw new MetaFolderException('Metafolder cannot be moved into itself or a contained subfolder.');
        }
        if (!$this->level) {
            throw new MetaFolderException('Root metafolder cannot be moved.');
        }

        $newRelPath = $destination->getRelativePath() . array_slice (explode('/', trim($this->getRelativePath(), '/')), -1)[0] . '/';

        try {
            $existingFolder = self::getInstance($newRelPath);
        }
        catch (MetaFolderException $e) {
        }
        if (isset($existingFolder)) {
            throw new MetaFolderException('Cannot move folder. Folder with same path already exists.');
        }

        // metafile updates

        $db = Application::getInstance()->getVxPDO();

        // handle nesting, kudos to https://rogerkeays.com/how-to-move-a-node-in-nested-sets-with-sql

        $subtreeWidth = $this->r - $this->l + 1;
        $newPos = $destination->l + 1;
        $subtreeDist = $newPos - $this->l;
        $tmpPos = $this->l;
        $levelDiff = $destination->level - $this->level + 1;

        // observe backward movement

        if($subtreeDist < 0) {
            $subtreeDist -= $subtreeWidth;
            $tmpPos += $subtreeWidth;
        }

        $db->beginTransaction();

        // create space for subtree at new position

        $db->execute('UPDATE folders SET l = l + ? WHERE l >= ?', [$subtreeWidth, $newPos]);
        $db->execute('UPDATE folders SET r = r + ? WHERE r >= ?', [$subtreeWidth, $newPos]);

        // move subtree into new space

        $db->execute('UPDATE folders SET l = l + ?, r = r + ?, level = level + (?) WHERE l >= ? AND r < ?', [$subtreeDist, $subtreeDist, $levelDiff, $tmpPos, $tmpPos + $subtreeWidth]);

        // remove space previously occupied by subtree

        $db->execute('UPDATE folders SET l = l - ? WHERE l > ?', [$subtreeWidth, $this->r]);
        $db->execute('UPDATE folders SET r = r - ? WHERE r > ?', [$subtreeWidth, $this->r]);

        // update path

        $db->execute('UPDATE folders SET path = REGEXP_REPLACE(path, ?, ?) WHERE path LIKE ?', ['^' . $this->getRelativePath(), $newRelPath, $this->getRelativePath() . '%']);
        $db->commit();

        self::refreshNestings();

        // move filesystem folder

        $this->filesystemFolder->move($destination->getFilesystemFolder());

        // unset cached instances

        unset(self::$instancesById[$this->id], self::$instancesByPath[$this->filesystemFolder->getPath()]);
        return self::getInstance(null, $this->id);
    }

    /**
     * rename metafolder
     * both filesystem folder and database entry are changed synchronously
     *
     * doesn't care about race conditions
     * warning: any references to the instance prior to renaming still exists and will yield invalid results
     *
     * @param string $to new filename
     * @return MetaFolder
     * @throws FilesystemFolderException
     * @throws MetaFolderException
     * @throws ApplicationException
     */
    public function rename(string $to): MetaFolder
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
            // update path of complete tree beneath renamed folder

            Application::getInstance()->getVxPDO()->execute(
                'UPDATE folders SET path = REPLACE(path, ?, ?) WHERE POSITION(? IN path) = 1',
                [
                    rtrim($this->getRelativePath(), DIRECTORY_SEPARATOR),
                    rtrim($newRelpath, DIRECTORY_SEPARATOR),
                    rtrim($this->getRelativePath(), DIRECTORY_SEPARATOR)
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
     * @return array|MetaFolder[]
     * @throws MetaFolderException
     * @throws ApplicationException
     * @throws FilesystemFolderException
     */
	public static function instantiateAllExistingMetaFolders(bool $force = false): array
    {
		foreach(
			Application::getInstance()->getVxPDO()->doPreparedQuery(
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

		return array_values(self::$instancesById);
	}

    /**
     * retrieve all folders with level 0
     *
     * @return MetaFolder[]
     * @throws MetaFolderException
     * @throws ApplicationException|FilesystemFolderException
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
     * @throws ApplicationException
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

			$db = Application::getInstance()->getVxPDO();

			if(strpos($f->getPath(), Application::getInstance()->getAbsoluteAssetsPath()) === 0) {
				$metaData['path'] = trim(substr($f->getPath(), strlen(Application::getInstance()->getAbsoluteAssetsPath())), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
			}
			else {
				$metaData['path'] = rtrim($f->getPath(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
			}

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

            self::refreshNestings();
		}

		return self::getInstance($f->getPath());
	}
}
