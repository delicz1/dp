<?php

namespace Proj\Base\Object\Form;

use AjaxUpdater;
use Dialog;
use Form;
use Proj\Base\Controller\FormController;
use Proj\Base\Object\Dialog\ProjDialog;
use Proj\Base\Entity\FormProfile;

/**
 *
 */
class FormProfileDeleteDialog extends \Dialog {

    /**
     * @param Form        $form
     * @param \Proj\Base\Entity\FormProfile $profile
     *
     * @return Dialog
     */
    public static function create(Form $form, FormProfile $profile) {
        $tr = $form->getTranslator();
        $message = $tr->getAndReplace('form.profile.confirm.delete', [$profile->getName()]);
        $option = Dialog::getDefaultOption();
        $option->dialog->title = $tr->get('form.delete');
        $option->dialog->minWidth = 260;
        $option->dialog->minHeight = 80;
        $option->dialog->image->cls = 'icon-form-35x35 profile-delete';
        $option->dialog->css->padding = '5px';
        $option->ajax = null;

        $deleteTitle = $tr->get('form.delete');
        $cancelTitle = $tr->get('form.cancel');
        $deleteIcon = "icon-system-20x20 ok";
        $cancelIcon = "icon-system-20x20 disable";
        $updater = AjaxUpdater::create('', FormController::ACTION_DELETE . '?id=' .
                                           $profile->getId(), Dialog::CUSTOM_CLOSE);

        $dialog = new self('', $message, $option);
        $dialog->addButton($updater->render(false, false), $deleteTitle, $deleteIcon);
        $dialog->addButton(Dialog::CUSTOM_CLOSE, $cancelTitle, $cancelIcon);

        $dialog->setCloseFunction(Dialog::CLOSE . $form->getHandler()->update());

        return $dialog;
    }
}