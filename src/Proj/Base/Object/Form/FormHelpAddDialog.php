<?php

namespace Proj\Base\Object\Form;

use Dialog;
use Proj\Base\Controller\FormController;
//use Proj\Base\Object\Dialog\ProjDialog;

/**
 *
 */
class FormHelpAddDialog extends Dialog {

    const DIV = '#formHelpAddDialog';

    /**
     * @param \FormItem $item
     *
     * @return Dialog
     */
    public static function create($item) {
        /** @var DoctrineForm|DoctrineFormTabs $form */
        $form = $item->getForm();
        $tr = $form->getTranslator();
        $option = Dialog::getDefaultOption();
        $option->dialog->title = $tr->get('form.add');
        $option->dialog->minWidth = 360;
        $option->dialog->minHeight = 220;
        $option->dialog->image->cls = 'glyphicon glyphicon-32 glyphicon-asterisk';
//        $option->dialog->refresh = true;

        $data = FormHelpAddForm::INPUT_FORM_ID . '=' . $form->getId() . '&';
        $data .= FormHelpAddForm::INPUT_INPUT_NAME . '=' . $item->getName() . '&';
        $data .= FormHelpAddForm::INPUT_LOCALE . '=' . $tr->getSystemLang()->getIso();
                 $option->ajax->option->jQAjax->type = 'POST';
        $dialog = new self(self::DIV, FormController::ACTION_HELP_ADD . '?' . $data, $option);
        $addForm = FormHelpAddForm::create(null, null, $form->getDoctrine(), $form->getFormater());
        $dialog->addSubmitButton($addForm[FormHelpAddForm::SUBMIT]);
        $dialog->setCloseFunction(Dialog::CLOSE . $form->getHandler()->update());

        return $dialog;
    }
}