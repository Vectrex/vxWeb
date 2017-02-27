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

use vxPHP\File\FilesystemFile;
use vxPHP\Application\Application;
use vxPHP\Observer\PublisherInterface;
use vxPHP\File\Exception\FilesystemFileException;

use vxWeb\Model\MetaFile\MetaFolder;
use vxWeb\Model\MetaFile\Exception\MetaFileException;
use vxWeb\Model\Article\Article;
use vxWeb\Model\Article\ArticleQuery;

/**
 * mapper for metafiles
 *
 * requires database tables files, folders
 *
 * @author Gregor Kofler
 *
 * @version 1.2.1 2017-02-28
 *
 * @todo merge rename() with commit()
 * @todo cleanup getImagesForReference()
 * @todo allow update of createdBy user
 */
class MetaFile implements PublisherInterface {

	/**
	 * retrieved instances accesible by their id 
	 * 
	 * @var MetaFile[]
	 */
	private static	$instancesById		= [];

	/**
	 * retrieved instances accesible by their path
	 *
	 * @var MetaFile[]
	 */
	private static	$instancesByPath	= [];

	/**
	 * @var FilesystemFile
	 */
	private $filesystemFile;

	/**
	 * @var MetaFolder
	 */
	private	$metaFolder;

	/**
	 * @var integer
	 */
	private	$id;
	
	/**
	 * @var boolean
	 */
	private	$isObscured;

	/**
	 * @var array
	 */
	private	$data;

	/**
	 * @var Article[]
	 */
	private	$linkedArticles;
	
	/**
	 * returns MetaFile instance alternatively identified by its path or its primary key in the database
	 *
	 * @param string $path
	 * @param integer $id
	 * @throws MetaFileException
	 *
	 * @return MetaFile
	 */
	public static function getInstance($path = NULL, $id = NULL) {

		if(isset($path)) {

			$lookup = Application::getInstance()->extendToAbsoluteAssetsPath($path);

			if(!isset(self::$instancesByPath[$lookup])) {
				$mf = new self($path);
				self::$instancesByPath[$lookup]		= $mf;
				self::$instancesById[$mf->getId()]	= $mf;
			}
			return self::$instancesByPath[$lookup];

		}

		else if(isset($id)) {

			if(!isset(self::$instancesById[$id])) {
				$mf = new self(NULL, $id);
				self::$instancesById[$id]								= $mf;
				self::$instancesByPath[$mf->filesystemFile->getPath()]	= $mf;
			}
			return self::$instancesById[$id];

		}

		else {
			throw new MetaFileException("Either file id or path required.");
		}
	}

	/**
	 * return array of MetaFiles identified by primary keys
	 *
	 * @param array $ids
	 *
	 * @return array
	 */
	public static function getInstancesByIds(array $ids) {

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
					CONCAT(fo.Path, IFNULL(f.Obscured_Filename, f.File)) as FullPath
				FROM
					files f
					INNER JOIN folders fo ON fo.foldersID = f.foldersID
				WHERE
					f.filesID IN (' . implode(',', array_fill(0, count($toRetrieveById), '?')) . ')',
			$toRetrieveById);

			foreach($rows as $row) {
				$mf = new self(NULL, NULL, $row);
				self::$instancesById[$mf->getId()]						= $mf;
				self::$instancesByPath[$mf->filesystemFile->getPath()]	= $mf;
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
	 * @return array
	 */
	public static function getInstancesByPaths(array $paths) {

		$toRetrieveByPath	= [];
		$lookupPaths		= [];

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

			$where = array_fill(0, count($toRetrieveByPath) / 3, 'f.File = ? AND fo.Path IN (?, ?)');

			$rows = Application::getInstance()->getDb()->doPreparedQuery('
				SELECT
					f.*,
					CONCAT(fo.Path, IFNULL(f.Obscured_Filename, f.File)) as FullPath
				FROM
					files f
					INNER JOIN folders fo ON fo.foldersID = f.foldersID
				WHERE
					'. implode(' OR ', $where),
			$toRetrieveByPath);

			foreach($rows as $row) {
				$mf = new self(NULL, NULL, $row);
				self::$instancesById[$mf->getId()]						= $mf;
				self::$instancesByPath[$mf->filesystemFile->getPath()]	= $mf;
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
	 * @param callback $callBackSort
	 *
	 * @return array metafiles
	 */
	public static function getMetaFilesInFolder(MetaFolder $folder, $callBackSort = NULL) {

		// instance all filesystem files in folder, to speed up things

		FilesystemFile::getFilesystemFilesInFolder($folder->getFilesystemFolder());

		$result = [];

		$files = Application::getInstance()->getDb()->doPreparedQuery("SELECT f.*, CONCAT(fo.Path, IFNULL(f.Obscured_Filename, f.File)) as FullPath FROM files f INNER JOIN folders fo ON f.foldersID = fo.foldersID WHERE fo.foldersID = ?", array((int) $folder->getId()));

		foreach($files as &$f) {
			if(isset(self::$instancesById[$f['filesID']])) {
				$file = self::$instancesById[$f['filesID']];
			}
			else {
				$file = new self(NULL, NULL, $f);
				self::$instancesById[$f['filesID']]							= $file;
				self::$instancesByPath[$file->filesystemFile->getPath()]	= $file;
			}
			$result[] = $file;
		}

		if(is_null($callBackSort)) {
			return $result;
		}
		else if(is_callable($callBackSort)) {
			usort($result, $callBackSort);
			return $result;
		}
		else if(is_callable("self::$callBackSort")) {
			usort($result, "self::$callBackSort");
			return $result;
		}
		else {
			throw new MetaFileException("'$callBackSort' is not callable.");
		}
	}

	/**
	 * return all metafile instances linked to an article
	 * 
	 * @param Article $article
	 * @param callback $callBackSort
	 * @throws MetaFileException
	 * @return array:\vxPHP\File\MetaFile
	 */
	public static function getFilesForArticle(Article $article, $callBackSort = NULL) {

		$result = [];
		
		$files = Application::getInstance()->getDb()->doPreparedQuery("
			SELECT
				f.*,
				CONCAT(fo.Path, IFNULL(f.Obscured_Filename, f.File)) AS FullPath
			FROM
				files f
				INNER JOIN folders fo ON f.foldersID = fo.foldersID
				INNER JOIN articles_files af ON af.filesID = f.filesID
			WHERE
				af.articlesID = ?
			ORDER BY
				af.customSort
			", array($article->getId()));
		
		foreach($files as &$f) {
			if(isset(self::$instancesById[$f['filesID']])) {
				$file = self::$instancesById[$f['filesID']];
			}
			else {
				$file = new self(NULL, NULL, $f);
				self::$instancesById[$f['filesID']]							= $file;
				self::$instancesByPath[$file->filesystemFile->getPath()]	= $file;
			}
			$result[] = $file;
		}
		
		if(is_null($callBackSort)) {
			return $result;
		}
		else if(is_callable($callBackSort)) {
			usort($result, $callBackSort);
			return $result;
		}
		else if(is_callable("self::$callBackSort")) {
			usort($result, "self::$callBackSort");
			return $result;
		}
		else {
			throw new MetaFileException("'$callBackSort' is not callable.");
		}
		
	}
	
	/**
	 * return all metafile instances linked to an article with mimetype 'image/jpeg', 'image/png', 'image/gif'
	 *
	 * @param Article $article
	 * @param callback $callBackSort
	 * @throws MetaFileException
	 * @return array:\vxPHP\File\MetaFile
	 */
	public static function getImagesForArticle(Article $article, $callBackSort = NULL) {
		
		$result = [];

		$mimeTypes = ['image/jpeg', 'image/png', 'image/gif'];

		$files = Application::getInstance()->getDb()->doPreparedQuery("
			SELECT
				f.*,
				CONCAT(fo.Path, IFNULL(f.Obscured_Filename, f.File)) as FullPath
			FROM
				files f
				INNER JOIN folders fo ON f.foldersID = fo.foldersID
				INNER JOIN articles_files af ON af.filesID = f.filesID
			WHERE
				af.articlesID = ?
				AND f.Mimetype IN ('".implode("','", $mimeTypes)."')
			ORDER BY
				af.customSort
			", [$article->getId()]);
				
		foreach($files as &$f) {
			if(isset(self::$instancesById[$f['filesID']])) {
				$file = self::$instancesById[$f['filesID']];
			}
			else {
				$file = new self(NULL, NULL, $f);
				self::$instancesById[$f['filesID']]							= $file;
				self::$instancesByPath[$file->filesystemFile->getPath()]	= $file;
			}
			$result[] = $file;
		}

		if(is_null($callBackSort)) {
			return $result;
		}
		else if(is_callable($callBackSort)) {
			usort($result, $callBackSort);
			return $result;
		}
		else if(is_callable("self::$callBackSort")) {
			usort($result, "self::$callBackSort");
			return $result;
		}
		else {
			throw new MetaFileException("'$callBackSort' is not callable.");
		}
	}

	/**
	 * check whether $filename is already taken by a metafile in folder $f
	 *
	 * @param string $filename
	 * @param MetaFolder $f
	 * @return boolean is_available
	 */
	public static function isFilenameAvailable($filename, MetaFolder $f) {

		// $filename is not available, if metafile with $filename is already instantiated

		if(isset(self::$instancesByPath[$f->getFullPath().$filename])) {
			return FALSE;
		}

		// check whether $filename is found in database entries

		return count(
			Application::getInstance()->
			getDb()->
			doPreparedQuery("
				SELECT
					filesID
				FROM
					files
				WHERE
					foldersID = ? AND
					( File LIKE ? OR Obscured_Filename LIKE ? )
				",
				array((int) $f->getId(), (string) $filename, (string) $filename)
			)
		) === 0;
	}

	/**
	 * creates a metafile instance
	 * requires either id or path stored in db
	 * when an array is passed to constructor
	 * it sets MetaFile::data directly; used internally to avoid extra db queries
	 *
	 * @param string $path of metafile
	 * @param integer $id of metafile
	 * @param array $data
	 */
	private function __construct($path = NULL, $id = NULL, array $dbEntry = NULL) {
		if(isset($path)) {
			$this->data = $this->getDbEntryByPath($path);
		}
		else if(isset($id)) {
			$this->data = $this->getDbEntryById($id);
		}
		else if(isset($dbEntry)) {
			$this->data = array_change_key_case($dbEntry, CASE_LOWER);
		}

		$this->id				= $this->data['filesid'];
		$this->filesystemFile	= FilesystemFile::getInstance(Application::getInstance()->extendToAbsoluteAssetsPath($this->data['fullpath']));
		$this->metaFolder		= MetaFolder::getInstance($this->filesystemFile->getFolder()->getPath());

		// when record features an obscured_filename, the FilesystemFile is bound to this obscured filename, while the metafile always references the non-obscured filename

		$this->isObscured		= $this->data['file'] !== $this->filesystemFile->getFilename();
	}

	public function __toString() {
		return $this->getPath();
	}

	/**
	 * retrieves file metadata stored in database
	 *
	 * @param string $path
	 * @throws MetaFileException
	 *
	 * @return array
	 */
	private function getDbEntryByPath($path) {

		$pathinfo = pathinfo($path);

		$rows = Application::getInstance()->getDb()->doPreparedQuery(
			"SELECT f.*, CONCAT(fo.Path, IFNULL(f.Obscured_Filename, f.File)) as FullPath FROM files f INNER JOIN folders fo ON fo.foldersID = f.foldersID WHERE f.File = ? AND fo.Path IN(?, ?) LIMIT 1",
			array(
				$pathinfo['basename'],
				$pathinfo['dirname'] . DIRECTORY_SEPARATOR,
				str_replace(Application::getInstance()->getAbsoluteAssetsPath(), '', $pathinfo['dirname']) . DIRECTORY_SEPARATOR
			)
		);

		if(isset($rows[0])) {
			return array_change_key_case($rows[0], CASE_LOWER);
		}
		else {
			throw new MetaFileException(sprintf("MetaFile database entry for '%s' not found.", $path));
		}
	}

	private function getDbEntryById($id) {

		$rows = Application::getInstance()->getDb()->doPreparedQuery(
			"SELECT f.*, CONCAT(fo.Path, IFNULL(f.Obscured_Filename, f.File)) as FullPath FROM files f INNER JOIN folders fo ON fo.foldersID = f.foldersID WHERE f.filesID = ?",
			array((int) $id)
		);

		if(isset($rows[0])) {
			return array_change_key_case($rows[0], CASE_LOWER);
		}
		else {
			throw new MetaFileException(sprintf("MetaFile database entry for id '%d' not found.", $id));
		}
	}

	/**
	 * get id of metafile
	 *
	 * @return integer
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * get any data stored with metafile in database entry
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
	 * stub, might be dropped entirely
	 * 
	 * @param Article $article
	 */
	public function linkArticle(Article $article) {
		
	}

	/**
	 * stub, might be dropped entirely
	 *
	 * @param Article $article
	 */
	public function unlinkArticle(Article $article) {
	
	}

	/**
	 * get all articles linked to a file
	 * 
	 * @return Article[]
	 */
	public function getLinkedArticles() {

		if(is_null($this->linkedArticles)) {

			$this->linkedArticles = ArticleQuery::create(Application::getInstance()->getDb())
				->innerJoin('articles_files af', 'a.articlesID = af.articlesID')
				->where('af.filesID = ?', array($this->id))
				->select();

		}
		
		return $this->linkedArticles;
	}

	/**
	 * retrieve mime type
	 *
	 * @param bool $force forces re-read of mime type
	 * @return string
	 */
	public function getMimetype($force = FALSE) {
		return $this->filesystemFile->getMimetype($force);
	}

	/**
	 * check whether mime type indicates web image
	 * (i.e. image/jpeg, image/gif, image/png)
	 *
	 * @param bool $force forces re-read of mime type
	 * @return boolean
	 */
	public function isWebImage($force = FALSE) {
		return $this->filesystemFile->isWebImage($force);
	}

	/**
	 * retrieve file info
	 *
	 * @return SplFileInfo
	 */
	public function getFileInfo() {
		return $this->filesystemFile->getFileInfo();
	}

	/**
	 * retrieves physical path of file
	 *
	 * @return string
	 */
	public function getPath() {
		return $this->filesystemFile->getPath();
	}

	/**
	 * returns path relative to assets path root
	 * NULL if file is outside assets path
	 *
	 * @param boolean $force
	 * @return string
	 */
	public function getRelativePath($force = FALSE) {
		return $this->filesystemFile->getRelativePath($force);
	}

	/**
	 * retrieves physical filename of file
	 *
	 * @return string
	 */
	public function getFilename() {
		return $this->filesystemFile->getFilename();
	}

	/**
	 * retrieves metafile name of file
	 * differs from physical filename, when file is obscured
	 *
	 * @return string
	 */
	public function getMetaFilename() {
		return $this->getData('file');
	}

	/**
	 * return metafolder of metafile
	 *
	 * @return MetaFolder
	 */
	public function getMetaFolder() {
		return $this->metaFolder;
	}

	/**
	 * returns filesystemfile of metafile
	 *
	 * @return FilesystemFile
	 */
	public function getFilesystemFile() {
		return $this->filesystemFile;
	}

	/**
	 * rename metafile
	 * both filesystem file and database entry are changed synchronously
	 *
	 * doesn't care about race conditions
	 *
	 * @param string $to new filename
	 * @throws Exception
	 */
	public function rename($to)	{

		// obscured files only need to rename the metadata

		$oldpath = $this->filesystemFile->getPath();
		$newpath = $this->filesystemFile->getFolder()->getPath() . $to;

		if(!$this->isObscured) {
			try {
				$this->filesystemFile->rename($to);
			}
			catch(FilesystemFileException $e) {
				throw new MetaFileException("Rename from '$oldpath' to '$newpath' failed. '$oldpath' already exists.");
			}
		}

		try {
			Application::getInstance()->getDb()->execute('UPDATE files SET File = ? WHERE filesID = ?', array($to, $this->id));
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
			throw new MetaFileException("Moving '{$this->getFilename()}' to '{$destination->getFullPath()}' failed.");
		}

		// update reference in db

		try {
			Application::getInstance()->getDb()->execute('UPDATE files SET foldersID = ? WHERE filesID = ?', array($destination->getId(), $this->id));
		}
		catch(\Exception $e) {
			throw new MetaFileException("Moving '{$this->getFilename()}' to '{$destination->getFullPath()}' failed.");
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
	 * @throws Exception
	 */
	public function delete($keepFilesystemFile = FALSE) {
		
		MetaFileEvent::create(MetaFileEvent::BEFORE_METAFILE_DELETE, $this)->trigger();

		if(Application::getInstance()->getDb()->deleteRecord('files', $this->id)) {
			unset(self::$instancesById[$this->id]);
			unset(self::$instancesByPath[$this->filesystemFile->getPath()]);

			if(!$keepFilesystemFile) {
				$this->filesystemFile->delete();
			}
		}
		else {
			throw new MetaFileException("Delete of metafile '{$this->filesystemFile->getPath()}' failed.");
		}
	}

	/**
	 * obscure filename
	 * renames filesystem file, then updates metafile data in db and sets isObscured flag
	 *
	 * @param string $obscuredFilename
	 */
	public function obscureTo($obscuredFilename) {

		// rename filesystem file

		$this->filesystemFile->rename($obscuredFilename);

		// set metafile db attributes
		$this->setMetaData(array('Obscured_Filename' => $obscuredFilename));

		// set isObscured flag
		$this->isObscured = TRUE;
	}

	/**
	 * updates meta data of metafile
	 * @param array $data new data
	 */
	public function setMetaData($data) {

		$data = array_change_key_case($data, CASE_LOWER);

		/*
		 * @todo improve this hack
		 */

		if(isset($data['customsort']) && trim($data['customsort']) === '') {
			$data['customsort'] = NULL;
		}

		unset($data['file']);
		unset($data['filesid']);
		
		$this->data = $data + $this->data;
		$this->commit();

	}

	/**
	 * commit changes to metadata by writing data to database
	 */
	private function commit() {
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
	 * @throws FilesystemFileException
	 * @return \vxPHP\File\MetaFile
	 */
	public static function createMetaFile(FilesystemFile $file) {
	
		$db = Application::getInstance()->getDb();
	
		if(count($db->doPreparedQuery("
			SELECT
				f.filesID
			FROM
				files f
				INNER JOIN folders fo ON fo.foldersID = f.foldersID
			WHERE
				f.File  COLLATE utf8_bin = ? AND
				fo.Path COLLATE utf8_bin = ?
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
	
		$mf		= MetaFolder::createMetaFolder($file->getFolder());
		$user	= Application::getInstance()->getCurrentUser();
	
		if(!($filesID = $db->insertRecord('files', [
			'foldersID'		=> $mf->getId(),
			'File'			=> $file->getFilename(),
			'Mimetype'		=> $file->getMimetype(),
			'createdBy'		=> is_null($user) ? NULL : $user->getAttribute('id')
		]))) {
			throw new FilesystemFileException(sprintf("Could not create metafile for '%s'.", $file->getFilename()), FilesystemFileException::METAFILE_CREATION_FAILED);
		}
		else {
			$mfile = MetaFile::getInstance(NULL, $filesID);
			FileEvent::create(FileEvent::AFTER_METAFILE_CREATE, $mfile)->trigger();

			return $mfile;
		}
	}
	
	
}
