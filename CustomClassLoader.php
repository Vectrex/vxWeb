<?php
class CustomClassLoader {

	private $includePath;
	private $subDirs;

	public function __construct($includePath = '') {

		$this->includePath = rtrim($includePath, DIRECTORY_SEPARATOR);

		$this->subDirs = array(
			'src',
			'src' . DIRECTORY_SEPARATOR . 'menu',
			'src' . DIRECTORY_SEPARATOR . 'controller'
		);

	}

	public static function create($includePath = '') {
		return new static($includePath);
	}

	/**
	 * Installs this class loader on the SPL autoload stack
	 */
	public function register() {
		spl_autoload_register(array($this, 'loadClass'));
	}

	/**
	 * Uninstalls this class loader from the SPL autoloader stack
	 */
	public function unregister() {
		spl_autoload_unregister(array($this, 'loadClass'));
	}

	/**
	 * Loads the given class or interface
	 *
	 * @param string $className The name of the class to load
	 * @return void
	 */
	public function loadClass($className) {

		foreach($this->subDirs as $subdir) {
			$path = $this->includePath . DIRECTORY_SEPARATOR . $subdir . DIRECTORY_SEPARATOR;

			if(file_exists($path . $className . '.php')) {
				require_once($path . $className . '.php');
			}

		}
	}
}
