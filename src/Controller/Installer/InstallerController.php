<?php

namespace App\Controller\Installer;

use DOMDocument;
use PHPUnit\Util\Json;
use vxPHP\Application\Application;
use vxPHP\Application\Exception\ApplicationException;
use vxPHP\Constraint\Validator\RegularExpression;
use vxPHP\Form\FormElement\FormElementFactory;
use vxPHP\Form\HtmlForm;
use vxPHP\Http\JsonResponse;
use vxPHP\Http\ParameterBag;
use vxPHP\Security\Password\PasswordEncrypter;
use vxPHP\Security\Password\PasswordGenerator;
use vxPHP\Template\SimpleTemplate;
use vxPHP\Controller\Controller;
use vxPHP\Http\Response;
use vxPHP\Util\Rex;

class InstallerController extends Controller
{
	protected function execute(): Response | JsonResponse
    {
        if ($this->request->getMethod() === 'POST') {

            try {
                $form = HtmlForm::create()
                    ->addElement(FormElementFactory::create('input', 'host', '', [], [], true, ['trim'], [new RegularExpression(Rex::NOT_EMPTY_TEXT)]))
                    ->addElement(FormElementFactory::create('input', 'user', '', [], [], true, ['trim'], [new RegularExpression(Rex::NOT_EMPTY_TEXT)]))
                    ->addElement(FormElementFactory::create('input', 'password', '', [], [], true, ['trim'], [new RegularExpression(Rex::NOT_EMPTY_TEXT)]))
                    ->addElement(FormElementFactory::create('input', 'port', '', [], [], false, ['trim'], [new RegularExpression('/^\d{2,5}$/')]))
                    ->addElement(FormElementFactory::create('input', 'dbname', '', [], [], true, ['trim'], [new RegularExpression(Rex::NOT_EMPTY_TEXT)]))
                    ->addElement(FormElementFactory::create('select', 'db_type', null, [], ['mysql' => 'MySQL', 'pgsql' => 'PostgreSQL'], true, [], [], 'Es muss ein Datenbanktreiber gewählt werden.'))
                    ->bindRequestParameters(new ParameterBag(json_decode($this->request->getContent(), true, 512, JSON_THROW_ON_ERROR)))
                    ->disableCsrfToken()
                    ->validate()
                ;
                $errors = $form->getFormErrors();

                if (!$errors) {
                    $values = $form->getValidFormValues();
                    switch ($values['db_type']) {
                        case 'mysql':
                            $port = $values['port'] ?: '3306';
                            $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s', $values['host'], $port, $values['dbname']);
                            $connection = new \PDO($dsn, $values['user'], $values['password']);
                            break;
                        case 'pgsql':
                            $port = $values['port'] ?: '5432';
                            $dsn = sprintf('pgsql:host=%s;port=%s;dbname=%s', $values['host'], $port, $values['dbname']);
                            $connection = new \PDO($dsn, $values['user'], $values['password']);
                            break;
                        default:
                            throw new \InvalidArgumentException('Kein gültiger Datenbanktreiber angegeben.');
                    }

                    $this->writeDbStructure($connection);
                    $adminPassword = PasswordGenerator::create();
                    $this->writeDbData($connection, $adminPassword);
                    $this->writeDbConfiguration(
                        $this->createDbConfiguration(array_merge($values->all(), ['dsn' => $dsn])),
                        Application::getInstance()->getRootPath() . 'ini/pdo_config.xml'
                    );

                    return new JsonResponse(['success' => true, 'message' => 'Datenbank erfolgreich angelegt.']);
                }

                return new JsonResponse(['success' => false, 'errors' => array_keys($errors)]);

            } catch (\Exception $e) {
                $errMsg = $e->getMessage();
                return new JsonResponse(['success' => false, 'message' => $errMsg]);
            }
        }

        // delete installer script

	    $installerFile = Application::getInstance()->getRootPath() . 'web/installer.php';

	    if(!is_null($this->request->query->get('delete'))) {

	        if(!is_writable($installerFile)) {
	            die(sprintf("Kann Datei '%s' nicht löschen.", $installerFile));
            }

	        unlink($installerFile);
	        return $this->redirect($this->request->getSchemeAndHttpHost() . rtrim(dirname($this->request->getScriptName()), '/') . (Application::getInstance()->getRouter()->getServerSideRewrite() ? '/admin' : '/admin.php'));
        };

	    // check whether paths are writable

        $pathChecks = [];
        $paths = ['view/default', 'ini', 'web/files'];

        foreach($paths as $path) {
            $pathChecks[$path] = [
                'writable' => $this->checkWritable(realpath(Application::getInstance()->getRootPath() . $path))
            ];
        }

        $pathsOk = count(array_filter($pathChecks, static function($p) { return $p['writable']; })) === count($paths);

        return new Response(
            SimpleTemplate::create('installer/installer.php')
                ->assign('path_checks', $pathChecks)
                ->assign('paths_ok', $pathsOk)
//                ->assign('password', isset($success) ? $adminPassword : '')
//                ->assign('installer_is_deletable', is_writable($installerFile))
//                ->assign('installer_file', $installerFile)
                ->display()
        );
	}

	private function checkWritable($dir): bool
    {
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
     * @throws \RuntimeException
     */
    private function writeDbStructure(\PDO $connection): void
    {
        $drivername = strtolower($connection->getAttribute(\PDO::ATTR_DRIVER_NAME));
        $path = Application::getInstance()->getRootPath() . 'resources' . DIRECTORY_SEPARATOR . 'sql' . DIRECTORY_SEPARATOR;
        $dump = @file_get_contents($path . $drivername . '_structure.sql');

        if (false === $dump) {
            throw new \RuntimeException($drivername . '_structure.sql not found.');
        }

        $connection->exec($dump);
    }

    /**
     * insert some necessary database entries
     *
     * @param \PDO $connection
     * @param $adminPassword
     * @throws \RuntimeException|ApplicationException
     */
    private function writeDbData(\PDO $connection, $adminPassword): void
    {
        $drivername = strtolower($connection->getAttribute(\PDO::ATTR_DRIVER_NAME));
        $path = Application::getInstance()->getRootPath() . 'resources' . DIRECTORY_SEPARATOR . 'sql' . DIRECTORY_SEPARATOR;
        $dump = @file_get_contents( $path . $drivername . '_data.sql');

        if(false === $dump) {
            throw new \RuntimeException($drivername . '_data.sql not found.');
        }

        $connection->beginTransaction();
        $connection->exec($dump);
        $connection->commit();

        $hashedPassword = (new PasswordEncrypter())->hashPassword($adminPassword);
        $stmt = $connection->prepare('UPDATE admin SET pwd = ? WHERE username = ?');
        $stmt->execute([$hashedPassword, 'admin']);
    }

    /**
     * turn config data into XML format
     *
     * @param array $config
     * @return DOMDocument
     */
    private function createDbConfiguration(array $config): DOMDocument
    {
	    $xmlDoc = new DOMDocument();
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
     * @param DOMDocument $xml
     * @param $filename
     */
    private function writeDbConfiguration(DOMDocument $xml, $filename): void
    {
        file_put_contents($filename, $xml->saveXML());
    }
}
