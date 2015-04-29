<?php
/**
 * Editace nakladu
 */

namespace Proj\BussinesTrip\Component\Form;

use Doctrine\Bundle\DoctrineBundle\Registry;
use nil\Html;
use Notificator;
use Proj\Base\Object\Form\DoctrineForm;
use Proj\Base\Object\Form\FormId;
use Proj\Base\Object\Locale\Formatter;
use Proj\BussinesTrip\Component\Dialog\EditTripPointDialog;
use Proj\BussinesTrip\Component\Grid\TripPointGrid;
use Proj\BussinesTrip\Controller\TripPointController;
use Proj\BussinesTrip\Entity\TripPoint;
use Proj\BussinesTrip\Entity\Trip;

/**
 * @author springer
 */
class EditTripPointForm extends DoctrineForm {

    //=====================================================
    //== Konstanty ========================================
    //=====================================================

    const ID     = FormId::EDIT_TRIP_POINT;
    const ACTION = TripPointController::EDIT_FORM;
    const NAME   = 'EditTripPointForm';

    const PARAM_USER         = 'user_id';
    const PARAM_TRIP         = 'trip_id';

    const SUBMIT = 'save';

    /** @var TripPoint     */
    private $tripPoint;
    /** @var  Trip */
    private $trip;

    //=====================================================
    //== Konfigurace ======================================
    //=====================================================


    /**
     * @param Formatter $formatter
     * @param \Request  $request
     * @param Registry  $doctrine
     * @param TripPoint   $tripPoint
     * @param Trip      $trip
     * @return EditUserForm
     */
    public static function create(Formatter $formatter, \Request $request = null, Registry $doctrine = null, TripPoint $tripPoint = null, Trip $trip = null) {
        $form = new self(self::NAME, self::ACTION, self::POST);
        $form->setFormater($formatter);
        $form->addSubmit(self::SUBMIT, 'form.save', 'glyphicon glyphicon-floppy-disk');
        if ($request instanceof \Request) {
            $form->setRequest($request);
            $form->doctrine = $doctrine;
            $form->tripPoint = $tripPoint;
            $form->trip = $trip;
            $form->setHelpManager(false);
            $form->init();
        }
        return $form;
    }

    protected function init() {

        $tr = $this->formater->getLangTranslator();
        $this->addHidden(TripPoint::COLUMN_ID, $this->tripPoint->getId());
        $this->addHidden(self::PARAM_TRIP, $this->trip->getId());

        $this->addText(TripPoint::COLUMN_POINT, $tr->get('trip.point.point'), $this->tripPoint->getPoint());
        $this->addDate(TripPoint::COLUMN_TIME_FROM, $tr->get('trip.point.time.from'), $this->tripPoint->getTimeFrom(), \FormItemDate::MODE_DATETIME);
        $this->addDate(TripPoint::COLUMN_TIME_TO, $tr->get('trip.point.time.to'), $this->tripPoint->getTimeTo(), \FormItemDate::MODE_DATETIME);
        $this->handle();
    }

    public function onSuccess() {

        $em = $this->getDoctrine()->getManager();
        $tripPoint = $this->tripPoint;

        $tripPoint->setTrip($this->trip);
        $tripPoint->setPoint($this[TripPoint::COLUMN_POINT]->getValue());
        $tripPoint->setTimeFrom($this[TripPoint::COLUMN_TIME_FROM]->getValue());
        $tripPoint->setTimeTo($this[TripPoint::COLUMN_TIME_TO]->getValue());

        $em->persist($tripPoint);
        $em->flush();

        Notificator::add('SUCCESS', '', Notificator::TYPE_INFO);
        $js = EditTripPointDialog::close(EditTripPointDialog::DIV);
        $js .= TripPointGrid::reload(TripPointGrid::ID);
        echo Html::el('script')->setHtml($js);
    }

    public function onError() {
        Notificator::add('ERROR', '', Notificator::TYPE_ERROR);
    }



    //=====================================================
    //== Options ==========================================
    //=====================================================


    //=====================================================
    //== Validace =========================================
    //=====================================================
}