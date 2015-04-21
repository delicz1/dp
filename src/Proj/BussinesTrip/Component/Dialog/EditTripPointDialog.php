<?php
/**
 * @author springer
 */

namespace Proj\BussinesTrip\Component\Dialog;
use Dialog;
use Proj\Base\Object\Locale\Formatter;
use Proj\BussinesTrip\Component\Form\EditExpenseForm;
use Proj\BussinesTrip\Component\Form\EditTripForm;
use Proj\BussinesTrip\Component\Form\EditTripPointForm;
use Proj\BussinesTrip\Component\Grid\TripPointGrid;
use Proj\BussinesTrip\Controller\ExpenseController;
use Proj\BussinesTrip\Controller\TripPointController;

/**
 * Class EditTripPointDialog
 */
class EditTripPointDialog extends Dialog {

    const DIV = '#editTripPointDialog';

    /**
     * @param Formatter $formatter
     * @param int       $tripId
     * @param null|int  $id
     * @return EditVehicleDialog
     */
    public static function create(Formatter $formatter, $tripId, $id = null) {
        $option = self::getDefaultOption();
        $tr = $formatter->getLangTranslator();
        $data ='?id='.$id. '&' . EditTripPointForm::PARAM_TRIP. '='. $tripId;
        $url = TripPointController::EDIT_FORM . $data;
        $option->dialog->title = $tr->get('trip.point.title');
        $option->dialog->description = $tr->get('dialog.title.edit');
        $option->dialog->minWidth = 200;
        $option->dialog->minHeight = 200;
        $option->dialog->image->cls = '';
        $option->dialog->refresh = true;
        $option->ajax->mask->settingLoadImage->src = '/bundles/nil/php/component/ajaxUpdater/js/JQAjaxUpdater/picture/load4.gif';
        $dialog = new self(self::DIV, $url, $option);

        $form = EditTripPointForm::create($formatter);
        $dialog->addSubmitButton($form[EditTripPointForm::SUBMIT]);
        return $dialog;
    }
}