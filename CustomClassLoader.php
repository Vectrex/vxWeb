<?php
class CustomClassLoader {

	private $includePath;
	private $subDirs;

	public function __construct($includePath = '') {
		
		$this->includePath = rtrim($includePath, DIRECTORY_SEPARATOR);

		$this->subDirs = array(
			'src',
			'pages',
			'pages' . DIRECTORY_SEPARATOR . 'admin'
		);

	}

	/**
	 * Installs this class loader on the SPL autoload stack.
	 */
	public function register() {
		spl_autoload_register(array($this, 'loadClass'));
	}

	/**
	 * Uninstalls this class loader from the SPL autoloader stack.
	 */
	public function unregister() {
			spl_autoload_unregister(array($this, 'loadClass'));
	}

	/**
	 * Loads the given class or interface.
	 *
	 * @param string $className The name of the class to load.
	 * @return void
	 */
	public function loadClass($className) {

		foreach($this->subDirs as $subdir) {
			$path = $this->includePath . DIRECTORY_SEPARATOR . $subdir . DIRECTORY_SEPARATOR;

			if(file_exists($path . $className . '.php')) {
				require_once($path . $className . '.php');
				return;
			}

			else if(file_exists($path . 'class.' . strtolower($className) . '.php')) {
				require_once($path . 'class.' . strtolower($className) . '.php');
				return;
			}
		}
		
		throw new \Exception("Class '$className' not found.");
	}
}
