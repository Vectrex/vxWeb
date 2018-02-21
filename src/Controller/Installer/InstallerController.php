<?php

namespace App\Controller\Installer;

use Resque\Socket\Exception;
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

	    // check whether view/default/* are writable

        $checks = [];
        $defaultViewPath = realpath(Application::getInstance()->getRootPath() . 'view/default');
        $checks['default_files_are_writable'] = $this->checkWritable($defaultViewPath);

        // check whether files in view/default can be created

        if(!($tmpfile = tempnam($defaultViewPath, 'vxweb_'))) {
            $checks['default_is_writable'] = false;
        }
        else {
            $checks['default_is_writable'] = true;
            unlink($tmpfile);
        }

        // check ini files and path

        $iniPath = realpath(Application::getInstance()->getRootPath() . 'ini');
        $checks['ini_files_are_writable'] = $this->checkWritable($iniPath);

        // database credentials form

        $form = HtmlForm::create('installer/db_settings.htm')
            ->addElement(FormElementFactory::create('input', 'host', '',	[],	[],	true, ['trim'], [new RegularExpression(Rex::NOT_EMPTY_TEXT)]))
            ->addElement(FormElementFactory::create('input', 'user', '', [], [], true, ['trim'], [new RegularExpression(Rex::NOT_EMPTY_TEXT)]))
            ->addElement(FormElementFactory::create('input', 'password', '', [], [], true, ['trim'], [new RegularExpression(Rex::NOT_EMPTY_TEXT)]))
            ->addElement(FormElementFactory::create('input', 'port', '', [], [], false, ['trim'], [new RegularExpression('/^\d{2,5}$/')]))
            ->addElement(FormElementFactory::create('input', 'dbname', '', [], [], true, ['trim'], [new RegularExpression(Rex::NOT_EMPTY_TEXT)]))
            ->addElement(FormElementFactory::create('select', 'db_type', null, [], ['mysql' => 'MySQL', 'pgsql' => 'PostgreSQL'], true, [], [], 'Es muss ein Datenbanktreiber gewählt werden.'))
        ;

        if($this->request->getMethod() === 'POST') {
            $form->bindRequestParameters($this->request->request)->validate();

            if(!$form->getFormErrors()) {
                $values = $form->getValidFormValues();

                try {
                    switch($values['db_type']) {
                        case 'mysql':
                            $dsn = sprintf('mysql:host=%s%s;dbname=%s', $values['host'], $values['port'] ? (';port=' . $values['port']) : '', $values['dbname']);
                            $connection = new \PDO($dsn, $values['user'], $values['password']);
                            break;
                        case 'pgsql':
                            $dsn = sprintf('pgsql:host=%s%s;dbname=%s', $values['host'], $values['port'] ? (';port=' . $values['port']) : '' , $values['dbname']);
                            $connection = new \PDO($dsn, $values['user'], $values['password']);
                            break;
                        default:
                            $connectionError = 'Kein gültiger Datenbanktreiber angegeben.';
                    }

                    if(isset($connection)) {
                        $this->writeDbStructure($connection);
                        $adminPassword = PasswordGenerator::create();
                        $this->writeDbData($connection, $adminPassword);
                        $this->writeDbConfiguration(array_merge($values->all(), ['dsn' => $dsn]));

                        $success = true;
                    }

                }
                catch(\PDOException $e) {
                    $connectionError = $e->getMessage();
                }
                catch(\Exception $e) {
                    $miscError = $e->getMessage();
                }


            }
        }

        return new Response(
            SimpleTemplate::create('installer/installer.php')
                ->assign('default_view_path', $defaultViewPath)
                ->assign('ini_path', $iniPath)
                ->assign('checks', $checks)
                ->assign('db_settings_form', !empty($success) ? '' : $form->render())
                ->assign('connection_error', $connectionError ?? '')
                ->assign('misc_error', $miscError ?? '')
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

    private function writeDbConfiguration(array $config) {

	    $xmlDoc = new \DOMDocument();
	    $vxpdo = $xmlDoc->appendChild($xmlDoc->createElement('vxpdo'));
	    $datasource = $vxpdo->appendChild($xmlDoc->createElement('datasource'));
	    $datasource->setAttribute('name', 'default');
	    $datasource->appendChild($xmlDoc->createElement('driver', $config['db_type']));
        $datasource->appendChild($xmlDoc->createElement('user', $config['user']));
        $datasource->appendChild($xmlDoc->createElement('password', $config['password']));
        $datasource->appendChild($xmlDoc->createElement('dsn', $config['dsn']));

        var_dump($xmlDoc->saveXML());

    }
}
