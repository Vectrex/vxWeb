<?php

namespace App\Controller\Installer;

use vxPHP\Application\Application;
use vxPHP\Constraint\Validator\RegularExpression;
use vxPHP\Form\FormElement\FormElementFactory;
use vxPHP\Form\HtmlForm;
use vxPHP\Template\SimpleTemplate;
use vxPHP\Controller\Controller;
use vxPHP\Http\Response;

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
            ->addElement(FormElementFactory::create('input', 'host', '',	[],	[],	true, ['trim']))
            ->addElement(FormElementFactory::create('input', 'user', '', [], [], true, ['trim']))
            ->addElement(FormElementFactory::create('input', 'password', '', [], [], true, ['trim']))
            ->addElement(FormElementFactory::create('input', 'port', '', [], [], false, ['trim'], [new RegularExpression('/^\d{2,5}$/')]))
            ->addElement(FormElementFactory::create('select', 'db_type', null, [], ['mysql' => 'MySQL', 'postgresql' => 'PostgreSQL'], true, [], [], 'Es muss ein Datenbanktreiber gewählt werden.'))
        ;

        return new Response(
            SimpleTemplate::create('installer/installer.php')
                ->assign('default_view_path', $defaultViewPath)
                ->assign('ini_path', $iniPath)
                ->assign('checks', $checks)
                ->assign('db_settings_form', $form->render())
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
}
