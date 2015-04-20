<?php
/**
 * @author springer
 */

namespace Proj\BussinesTrip\Component\Dialog;
use Dialog;
use Proj\Base\Object\Locale\Formatter;
use Proj\BussinesTrip\Component\Form\EditTripForm;
use Proj\BussinesTrip\Component\Form\EditTripUserForm;

/**
 * Class AddTripUserDialog
 */
class EditTripUserDialog extends Dialog {

    const DIV = '#editTripUserDialog';

    /**
     * @param Formatter $formatter
     * @param null|int  $id
     * @param null      $tripId
     * @return EditVehicleDialog
     */
    public static function create(Formatter $formatter, $id = null, $tripId = null) {
        $option = self::getDefaultOption();
        $tr = $formatter->getLangTranslator();
        $data ='?id='.$id. '&' . EditTripUserForm::INPUT_TRIP . '='. $tripId;
        $url = EditTripUserForm::ACTION . $data ;
        $option->dialog->title = $tr->get('trip.bussines.trip');
        $option->dialog->description = $tr->get('dialog.title.edit');
        $option->dialog->minWidth = 200;
        $option->dialog->minHeight = 100;
        $option->dialog->image->cls = '';
        $option->dialog->refresh = true;
        $option->ajax->mask->settingLoadImage->src = '/bundles/nil/php/component/ajaxUpdater/js/JQAjaxUpdater/picture/load4.gif';
        $dialog = new self(self::DIV, $url, $option);

        $form = EditTripUserForm::create($formatter);
        $dialog->addSubmitButton($form[EditTripUserForm::SUBMIT]);


        return $dialog;
    }
}