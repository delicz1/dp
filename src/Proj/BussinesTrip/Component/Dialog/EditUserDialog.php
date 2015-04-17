<?php
/**
 * @author springer
 */

namespace Proj\BussinesTrip\Component\Dialog;
use Dialog;
use Proj\Base\Object\Locale\Formatter;
use Proj\BussinesTrip\Component\Form\EditUserForm;

/**
 * Class UserEditDialog
 */
class EditUserDialog extends Dialog {

    const DIV = '#editUserDialog';

    /**
     * @param Formatter $formatter
     * @return self
     */
    public static function create(Formatter $formatter) {
        $option = self::getDefaultOption();
        $tr = $formatter->getLangTranslator();
        $option->dialog->title = $tr->get('user.user');
        $option->dialog->description = $tr->get('dialog.title.edit');
        $option->dialog->minWidth = 200;
        $option->dialog->minHeight = 200;
        $option->dialog->image->cls = '';
        $option->dialog->refresh = true;
        $option->ajax->mask->settingLoadImage->src = '/bundles/nil/php/component/ajaxUpdater/js/JQAjaxUpdater/picture/load4.gif';
        $dialog = new self(self::DIV, EditUserForm::ACTION, $option);

        $form = EditUserForm::create($formatter);
        $dialog->addSubmitButton($form[EditUserForm::SUBMIT]);

        return $dialog;
    }
}