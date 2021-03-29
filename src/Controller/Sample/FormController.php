<?php

namespace App\Controller\Sample;

use vxPHP\Controller\Controller;
use vxPHP\Form\FormElement\ButtonElement;
use vxPHP\Form\FormElement\CheckboxElement;
use vxPHP\Form\FormElement\FormElementWithOptions\MultipleSelectElement;
use vxPHP\Form\FormElement\FormElementWithOptions\RadioElement;
use vxPHP\Form\FormElement\FormElementWithOptions\RadioOptionElement;
use vxPHP\Form\FormElement\FormElementWithOptions\SelectElement;
use vxPHP\Form\FormElement\FormElementWithOptions\SelectOptionElement;
use vxPHP\Form\FormElement\InputElement;
use vxPHP\Form\FormElement\LabelElement;
use vxPHP\Form\FormElement\PasswordInputElement;
use vxPHP\Form\FormElement\TextareaElement;
use vxPHP\Form\HtmlForm;
use vxPHP\Http\Response;
use vxPHP\Template\SimpleTemplate;

class FormController extends Controller
{
    protected function execute()
    {
        $form = $this->generateForm();

        $formTpl = (new SimpleTemplate())->setRawContents($form->render());
        $tpl = new SimpleTemplate('sample/layout.php');
        $tpl->insertTemplateAt($formTpl, 'content_block');

        return new Response($tpl->display());

    }

    protected function includeForm()
    {
        return new Response(
            (new SimpleTemplate())
                ->setRawContents('<div class="hero bg-secondary px-2">' . $this->generateForm()->render() . '</div>')
                ->display()
        );
    }

    protected function generateForm()
    {

        $checkboxTpl = <<<'EOD'
            <label class="form-checkbox">
                <input type="checkbox" name="<?= $this->element->getName() ?>" <?php if($this->element->getChecked()): ?> checked="checked"<?php endif; ?>>
                <i class="form-icon"></i> <?= $this->element->getLabel()->getLabelText() ?>
            </label>
EOD;

        $optionTpl = <<<'EOD'
          <label class="form-radio">
            <input type="radio" value="<?= $this->fragment->getValue() ?>" name="<?= $this->fragment->getParentElement()->getName() ?>" <?php if($this->fragment->getSelected()): ?> checked="checked"<?php endif; ?>>
            <i class="form-icon"></i> <?= $this->fragment->getLabel()->getLabelText() ?>
          </label>
EOD;

        $optionTemplate = (new SimpleTemplate())->setRawContents($optionTpl);
        $checkboxTemplate = (new SimpleTemplate())->setRawContents($checkboxTpl);

        $input = (new InputElement('input_element'))
            ->setLabel(new LabelElement('Enter your new identity'))
        ;
        $password = (new PasswordInputElement('password_element'))
            ->setLabel(new LabelElement('Enter your secret passphrase'))
        ;
        $checkbox = (new CheckboxElement('checkbox_element'))
            ->setLabel(new LabelElement('Do you have a licence to kill?'))
            ->setAttribute('id', 'form-checkbox')
            ->setSimpleTemplate($checkboxTemplate)
        ;

        $radio = new RadioElement('radio_element');

        foreach(['ppk' => 'Walther PPK', 'b' => 'Beretta 418', 'p99' => 'Walther P99'] as $key => $value) {
            $radio->appendOption((new RadioOptionElement($key, new LabelElement($value)))->setSimpleTemplate($optionTemplate));
        }

        $button = (new ButtonElement('submit', null, 'submit'))
            ->setInnerHTML('Enroll me to MI6')
        ;

        $textarea = (new TextareaElement('textarea_element'))
            ->setLabel(new LabelElement('Describe your abilities'))
        ;

        $select = (new SelectElement('select_element'))
            ->setLabel(new LabelElement('Pick a companion'))
            ->appendOption((new SelectOptionElement('0', 'Must be a girl...'))->setAttribute('disabled', 'disabled'))
        ;

        foreach(['1' => 'Ursula Andress', '2' => 'Honor Blackman', '3' => 'Diana Rigg', '4' => 'Barbara Bach', '5' => 'Eva Green'] as $key => $value) {
            $select->appendOption(new SelectOptionElement($key, $value));
        }

        $multipleSelect = (new MultipleSelectElement('multiple_select_element'))
            ->createOptions(['1' => 'Sean Connery', '2' => 'George Lazenby', '3' => 'Roger Moore', '4' => 'Timothy Dalton', '5' => 'Pierce Brosnan', '6' => 'Daniel Craig'])
            ->setLabel(new LabelElement('Pick your Bonds'))
        ;

        $form = new HtmlForm('sample/form.htm');

        $form
            ->addElement($input)
            ->addElement($password)
            ->addElement($checkbox)
            ->addElement($select)
            ->addElement($multipleSelect)
            ->addElement($radio)
            ->addElement($textarea)
            ->addElement($button)
        ;

        $inputs = [];

        for ($i = 1; $i <= 3; ++$i) {
            $inputs[] = (new InputElement('input_array'))->setLabel(new LabelElement('henchman ' . $i));
        }

        $form->addElementArray($inputs);

        $form->bindRequestParameters();

        return $form;
    }
}