<?php
class CustomClassLoader {

	public function __construct() {
		$includePaths = array(
			'classes',
			'pages',
			'pages' . DIRECTORY_SEPARATOR . 'admin'
		);

		set_include_path(get_include_path().PATH_SEPARATOR.implode(PATH_SEPARATOR, $includePaths));
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
		require_once('class.' . strtoLower($className) . '.php');
	}
}
