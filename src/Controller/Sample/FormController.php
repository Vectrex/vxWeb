<?php

namespace App\Controller\Sample;

use vxPHP\Controller\Controller;
use vxPHP\Form\FormElement\ButtonElement;
use vxPHP\Form\FormElement\CheckboxElement;
use vxPHP\Form\FormElement\FormElementWithOptions\RadioElement;
use vxPHP\Form\FormElement\FormElementWithOptions\SelectElement;
use vxPHP\Form\FormElement\InputElement;
use vxPHP\Form\FormElement\LabelElement;
use vxPHP\Form\FormElement\TextareaElement;
use vxPHP\Form\HtmlForm;
use vxPHP\Http\Response;
use vxPHP\Template\SimpleTemplate;

class FormController extends Controller
{

    protected function execute()
    {

        $input = (new InputElement('input_element'))->setLabel(new LabelElement('An input element'));
        $checkbox = (new CheckboxElement('checkbox_element'))->setLabel(new LabelElement('A checkbox to click'))->setAttribute('id', 'form-checkbox');
        $select = (new SelectElement('select_element'))->createOptions(['-1' => 'pick an animal...', 'dog' => 'Dog', 'cat' => 'Cat'])->setLabel(new LabelElement('A select element'));
        $radio = (new RadioElement('radio_element'))->createOptions(['n' => 'no', 'nn' => 'nope', 'nnn' => 'never']);
        $button = (new ButtonElement('submit', null, 'submit'))->setInnerHTML('submit form');
        $textarea = (new TextareaElement('textarea_element'))->setLabel(new LabelElement('A textarea element'));

        $form = new HtmlForm('sample/form.htm');

        $form
            ->addElement($input)
            ->addElement($checkbox)
            ->addElement($select)
            ->addElement($radio)
            ->addElement($textarea)
            ->addElement($button)
        ;

        $inputs = [];

        for ($i = 1; $i <= 3; ++$i) {
            $inputs[] = (new InputElement('input_array'))->setLabel(new LabelElement('input array ' . $i));
        }

        $form->addElementArray($inputs);

        $formTpl = (new SimpleTemplate())->setRawContents($form->render());
        $tpl = new SimpleTemplate('layout.php');
        $tpl->insertTemplateAt($formTpl, 'content_block');

        return new Response($tpl->display());


    }
}