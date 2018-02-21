<?php

namespace App\Controller\Installer;

use vxPHP\Application\Application;
use vxPHP\Constraint\Validator\RegularExpression;
use vxPHP\Form\FormElement\FormElementFactory;
use vxPHP\Form\HtmlForm;
use vxPHP\Security\Password\PasswordEncrypter;
use vxPHP\Security\Password\PasswordGenerator;
use vxPHP\Template\SimpleTemplate;
use vxPHP\Controller\Controller;
use vxPHP\Http\Response;
use vxPHP\Util\Rex;

class InstallerController extends Controller {

	protected function execute() {

	    $installerFile = Application::getInstance()->getRootPath() . 'web/installer.php';

	    if(!is_null($this->request->query->get('delete'))) {

	        if(!is_writeable($installerFile)) {
	            die(sprintf("Kann Datei '%s' nicht löschen.", $installerFile));
            }

	        unlink($installerFile);
	        return $this->redirect($this->request->getSchemeAndHttpHost() . (Application::getInstance()->hasNiceUris() ? '/admin' : 'admin.php'));
        };

	    // check whether paths are writable

        $pathChecks = [];
        $paths = ['view/default', 'ini', 'web/files'];

        foreach($paths as $path) {
            $pathChecks[$path] = [
                'writable' => $this->checkWritable(realpath(Application::getInstance()->getRootPath() . $path))
            ];
        }

        $pathsOk = count(array_filter($pathChecks, function($p) { return $p['writable']; })) === count($paths);

        if($pathsOk) {

            // database credentials form

            $form = HtmlForm::create('installer/db_settings.htm')
                ->addElement(FormElementFactory::create('input', 'host', '', [], [], true, ['trim'], [new RegularExpression(Rex::NOT_EMPTY_TEXT)]))
                ->addElement(FormElementFactory::create('input', 'user', '', [], [], true, ['trim'], [new RegularExpression(Rex::NOT_EMPTY_TEXT)]))
                ->addElement(FormElementFactory::create('input', 'password', '', [], [], true, ['trim'], [new RegularExpression(Rex::NOT_EMPTY_TEXT)]))
                ->addElement(FormElementFactory::create('input', 'port', '', [], [], false, ['trim'], [new RegularExpression('/^\d{2,5}$/')]))
                ->addElement(FormElementFactory::create('input', 'dbname', '', [], [], true, ['trim'], [new RegularExpression(Rex::NOT_EMPTY_TEXT)]))
                ->addElement(FormElementFactory::create('select', 'db_type', null, [], ['mysql' => 'MySQL', 'postgresql' => 'PostgreSQL'], true, [], [], 'Es muss ein Datenbanktreiber gewählt werden.'));

            if ($this->request->getMethod() === 'POST') {
                $form->bindRequestParameters($this->request->request)->validate();

                if (!$form->getFormErrors()) {
                    $values = $form->getValidFormValues();

                    try {
                        switch ($values['db_type']) {
                            case 'mysql':
                                $dsn = sprintf('mysql:host=%s%s;dbname=%s', $values['host'], $values['port'] ? (';port=' . $values['port']) : '', $values['dbname']);
                                $connection = new \PDO($dsn, $values['user'], $values['password']);
                                break;
                            case 'postgresql':
                                $dsn = sprintf('pgsql:host=%s%s;dbname=%s', $values['host'], $values['port'] ? (';port=' . $values['port']) : '', $values['dbname']);
                                $connection = new \PDO($dsn, $values['user'], $values['password']);
                                break;
                            default:
                                $connectionError = 'Kein gültiger Datenbanktreiber angegeben.';
                        }

                        if (isset($connection)) {
                            $this->writeDbStructure($connection);
                            $adminPassword = PasswordGenerator::create();
                            $this->writeDbData($connection, $adminPassword);
                            $this->writeDbConfiguration(
                                $this->createDbConfiguration(array_merge($values->all(), ['dsn' => $dsn])),
                                Application::getInstance()->getRootPath() . 'ini/pdo_config.xml'
                            );

                            $success = true;
                        }

                    } catch (\PDOException $e) {
                        $connectionError = $e->getMessage();
                    } catch (\Exception $e) {
                        $miscError = $e->getMessage();
                    }


                }
            }

        }

        return new Response(
            SimpleTemplate::create('installer/installer.php')
                ->assign('path_checks', $pathChecks)
                ->assign('paths_ok', $pathsOk)
                ->assign('db_settings_form', (!isset($form)) ? '' : $form->render())
                ->assign('connection_error', $connectionError ?? '')
                ->assign('misc_error', $miscError ?? '')
                ->assign('success', $success ?? '')
                ->assign('password', isset($success) ? $adminPassword : '')
                ->assign('admin_url', isset($success) ? ($this->request->getSchemeAndHttpHost() . (Application::getInstance()->hasNiceUris() ? '/admin' : 'admin.php')) : '')
                ->assign('installer_is_deletable', is_writeable($installerFile))
                ->assign('installer_file', $installerFile)
                ->display()
        );

	}

	private function checkWritable($dir) {

        if (is_dir($dir)) {
            if(is_writable($dir)) {
                $objects = scandir($dir);

                foreach ($objects as $object) {
                    if ($object !== '.' && $object !== '..') {
                        if (!$this->checkWritable($dir . DIRECTORY_SEPARATOR . $object)) {
                            return false;
                        }
                    }
                }

                return true;
            }
            else{
                return false;
            }

        }
        else if(file_exists($dir)) {
            return (is_writable($dir));
        }
    }

    /**
     * execute queries to create database structure
     *
     * @param \PDO $connection
     * @throws \Exception
     */
    private function writeDbStructure(\PDO $connection) {

	    $drivername = strtolower($connection->getAttribute(\PDO::ATTR_DRIVER_NAME));
        $dump = @file_get_contents( __DIR__ . DIRECTORY_SEPARATOR . $drivername . '_structure.sql');
        if(false === $dump) {
            throw new \Exception($drivername . '_structure.sql not found.');
        }
        $connection->beginTransaction();
        $connection->exec($dump);
        $connection->commit();

    }

    /**
     * insert some necessary database entries
     *
     * @param \PDO $connection
     * @param $adminPassword
     * @throws \Exception
     */
    private function writeDbData(\PDO $connection, $adminPassword) {

        $drivername = strtolower($connection->getAttribute(\PDO::ATTR_DRIVER_NAME));
        $dump = @file_get_contents( __DIR__ . DIRECTORY_SEPARATOR . $drivername . '_data.sql');
        if(false === $dump) {
            throw new \Exception($drivername . '_data.sql not found.');
        }
        $connection->beginTransaction();
        $connection->exec($dump);
        $stmt = $connection->prepare('UPDATE admin SET pwd = ? WHERE username = ?');
        $stmt->execute([(new PasswordEncrypter())->hashPassword($adminPassword), 'admin']);
        $connection->commit();

    }

    /**
     * turn config data into XML format
     *
     * @param array $config
     * @return \DOMDocument
     */
    private function createDbConfiguration(array $config) {

	    $xmlDoc = new \DOMDocument();
	    $vxpdo = $xmlDoc->appendChild($xmlDoc->createElement('vxpdo'));
	    $datasource = $vxpdo->appendChild($xmlDoc->createElement('datasource'));
	    $datasource->setAttribute('name', 'default');
	    $datasource->appendChild($xmlDoc->createElement('driver', $config['db_type']));
        $datasource->appendChild($xmlDoc->createElement('user', $config['user']));
        $datasource->appendChild($xmlDoc->createElement('password', $config['password']));
        $datasource->appendChild($xmlDoc->createElement('dsn', $config['dsn']));

        return $xmlDoc;

    }

    /**
     * write XML configuration
     *
     * @param \DOMDocument $xml
     * @param $filename
     */
    private function writeDbConfiguration(\DOMDocument $xml, $filename) {

        file_put_contents($filename, $xml->saveXML());

    }
}
