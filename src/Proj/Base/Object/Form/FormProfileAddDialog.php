<?php

namespace Proj\Base\Object\Form;

use Dialog;
use Proj\Base\Controller\FormController;

/**
 *
 */
class FormProfileAddDialog extends \Dialog {

    const DIV = '#formProfileAddDialog';

    /**
     * @param DoctrineForm|DoctrineFormTabs $form
     *
     * @return Dialog
     */
    public static function create($form) {
        $tr = $form->getTranslator();
        $option = Dialog::getDefaultOption();
        $option->dialog->title = $tr->get('form.add');
        $option->dialog->minWidth = 240;
        $option->dialog->minHeight = 140;
        $option->dialog->image->cls = 'icon-form-35x35 profile-add';

        $data = '__profile_fid=' . $form->getId() . '&';
        foreach ($form->items as $item) {
            if ($item->getProfile() || $item->getProfileIf()) {
                $value = $item->getValue();
                if (is_callable($item->profileEncode)) {
                    $fn = $item->profileEncode;
                    $value = $fn($value);
                }
                $data .= FormProfileAddForm::INPUT_CONFIG_PREFIX . $item->getName() . '=' . serialize($value) . '&';
            }
        }
        $option->ajax->option->jQAjax->type = 'POST';

        $dialog = new self(self::DIV, FormController::ACTION_ADD . '?' . $data, $option);
        $addForm = FormProfileAddForm::create(null, null, $form->getDoctrine(), $form->getFormater());
        $dialog->addSubmitButton($addForm[FormProfileAddForm::SUBMIT]);
        $dialog->setCloseFunction(Dialog::CLOSE . $form->getHandler()->update());

        return $dialog;
    }
}