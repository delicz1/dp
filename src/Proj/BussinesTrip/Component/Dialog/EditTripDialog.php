<?php
/**
 * @author springer
 */

namespace Proj\BussinesTrip\Component\Dialog;
use Dialog;
use Proj\Base\Object\Locale\Formatter;
use Proj\BussinesTrip\Component\Form\EditTripForm;
use Proj\BussinesTrip\Component\Form\EditVehicleForm;

/**
 * Class EditTripDialog
 */
class EditTripDialog extends Dialog {

    const DIV = '#editTripDialog';

    /**
     * @param Formatter $formatter
     * @param null|int  $id
     * @return EditVehicleDialog
     */
    public static function create(Formatter $formatter, $id = null) {
        $option = self::getDefaultOption();
        $tr = $formatter->getLangTranslator();
        $url = EditTripForm::ACTION . ($id ? '?id=' . $id : '');
        $option->dialog->title = $tr->get('trip.bussines.trip');
        $option->dialog->description = $tr->get('dialog.title.edit');
        $option->dialog->minWidth = 200;
        $option->dialog->minHeight = 200;
        $option->dialog->image->cls = '';
        $option->dialog->refresh = true;
        $option->ajax->mask->settingLoadImage->src = '/bundles/nil/php/component/ajaxUpdater/js/JQAjaxUpdater/picture/load4.gif';
        $dialog = new self(self::DIV, $url, $option);

        $form = EditTripForm::create($formatter);
        $dialog->addSubmitButton($form[EditTripForm::SUBMIT]);

        return $dialog;
    }
}