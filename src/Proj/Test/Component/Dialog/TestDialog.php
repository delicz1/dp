<?php
/**
 * @author necas
 */

namespace Proj\Test\Component\Dialog;
use Dialog;
use Proj\Base\Object\Locale\Formatter;
use Proj\Test\Component\Form\AddUserForm;

/**
 * Class UserEditDialog
 */
class TestDialog extends Dialog {

    const DIV = '#testDialog';

    /**
     * @param Formatter $formatter
     * @return TestDialog
     */
    public static function create(Formatter $formatter) {
        $url = '/testForm';
        $option = self::getDefaultOption();
        $option->dialog->title = 'Title';
        $option->dialog->description = 'Desc';
        $option->dialog->minWidth = 200;
        $option->dialog->minHeight = 200;
        $option->dialog->image->cls = '';
        $option->dialog->refresh = true;
        $option->ajax->mask->settingLoadImage->src = '/bundles/nil/php/component/ajaxUpdater/js/JQAjaxUpdater/picture/load4.gif';
        $dialog = new self(self::DIV, $url, $option);

        $form = AddUserForm::create($formatter);
        $dialog->addSubmitButton($form[AddUserForm::SUBMIT]);

        return $dialog;
    }
}