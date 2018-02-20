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

        $defaultViewPath = realpath(Application::getInstance()->getRootPath() . 'view/default');
        $defaultFilesAreWritable = $this->checkWritable($defaultViewPath);

        // check whether files in view/default can be created

        if(!($tmpfile = tempnam($defaultViewPath, 'vxweb_'))) {
            $defaultIsWritable = false;
        }
        else {
            $defaultIsWritable = true;
            unlink($tmpfile);
        }

        // check ini files and path

        $iniPath = realpath(Application::getInstance()->getRootPath() . 'ini');
        $iniFilesAreWritable = $this->checkWritable($iniPath);

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
                ->assign('default_is_writable', $defaultIsWritable)
                ->assign('default_files_are_writable', $defaultFilesAreWritable)
                ->assign('ini_files_are_writable', $iniFilesAreWritable)
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
