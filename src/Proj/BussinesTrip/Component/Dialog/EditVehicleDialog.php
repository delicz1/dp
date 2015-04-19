<?php
/**
 * @author springer
 */

namespace Proj\BussinesTrip\Component\Dialog;
use Dialog;
use Proj\Base\Object\Locale\Formatter;
use Proj\BussinesTrip\Component\Form\EditVehicleForm;

/**
 * Class EditVehicleDialog
 */
class EditVehicleDialog extends Dialog {

    const DIV = '#editVehicleDialog';

    /**
     * @param Formatter $formatter
     * @param null|int  $id
     * @return EditVehicleDialog
     */
    public static function create(Formatter $formatter, $id = null) {
        $option = self::getDefaultOption();
        $tr = $formatter->getLangTranslator();
        $url = EditVehicleForm::ACTION . ($id ? '?id=' . $id : '');
        $option->dialog->title = $tr->get('vehicle.vehicle');
        $option->dialog->description = $tr->get('dialog.title.edit');
        $option->dialog->minWidth = 200;
        $option->dialog->minHeight = 200;
        $option->dialog->image->cls = '';
        $option->dialog->refresh = true;
        $option->ajax->mask->settingLoadImage->src = '/bundles/nil/php/component/ajaxUpdater/js/JQAjaxUpdater/picture/load4.gif';
        $dialog = new self(self::DIV, $url, $option);

        $form = EditVehicleForm::create($formatter);
        $dialog->addSubmitButton($form[EditVehicleForm::SUBMIT]);

        return $dialog;
    }
}