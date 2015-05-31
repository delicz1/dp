<?php
/**
 * @author springer
 */

namespace Proj\BussinesTrip\Controller;

use Proj\BussinesTrip\Component\Dialog\EditTripDialog;
use Proj\BussinesTrip\Component\Dialog\EditExpenseDialog;
use Proj\BussinesTrip\Component\Dialog\EditTripPointDialog;
use Proj\BussinesTrip\Component\Dialog\EditTripUserDialog;
use Proj\BussinesTrip\Component\Form\EditTripUserForm;
use Proj\BussinesTrip\Component\Form\EditTripForm;
use Proj\BussinesTrip\Component\Form\TripGridFilterForm;
use Proj\BussinesTrip\Component\Form\TripGridUserForm;
use Proj\BussinesTrip\Component\Grid\ExpenseGrid;
use Proj\BussinesTrip\Component\Grid\TripGrid;
use Proj\BussinesTrip\Component\Grid\TripPointGrid;
use Proj\BussinesTrip\Component\Grid\TripUserGrid;
use Proj\BussinesTrip\Entity\Trip;
use Proj\BussinesTrip\Entity\TripUser;
use /** @noinspection PhpUnusedAliasInspection */
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use /** @noinspection PhpUnusedAliasInspection */
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Proj\Base\Controller\BaseController;

/**
 * @Route("trip", name="_trip")
 * @Template()
 */
class TripController extends BaseController {

    const TRIP_DETAIL      = '/trip/detail';
    const TRIP_DETAIL_DATA = '/trip/detailData';

    const EDIT_FORM           = '/trip/editForm';
    const EDIT_TRIP_USER_FORM = '/trip/editTripUserForm';
    const GRID_DATA           = '/trip/grid';

    /**
     * @Route("/index")
     * @Template()
     */
    public function indexAction() {

        $user = $this->getSelfUser();
        $formatter = $this->getFormater();
        $dialog = EditTripDialog::create($formatter);
        $grid = new TripGrid($this->getLangTranslator(), $this->getDoctrine());
        $grid->setFilter(TripGridFilterForm::create($formatter, $grid, $this->getDoctrine(), $this->getRequestNil()));
        return ['grid' => $grid, 'dialog' => $dialog, 'user' =>$user];
    }

    /**
     * @Route("/detail")
     * @Template()
     */
    public function tripDetailAction() {
        $formatter = $this->getFormater();
        $doctrine = $this->getDoctrine();
        $tr = $this->getLangTranslator();
        $id = $this->getRequestNil()->getParam(Trip::COLUMN_ID);
        $trip = $this->getDoctrine()->getRepository('ProjBussinesTripBundle:Trip')->find($id);
        return [
            'trip'         => $trip,
            'formatter'    => $this->getFormater(),
            'tripUserGrid' => new TripUserGrid($tr, $doctrine, $trip),
            'expenseGrid'  => new ExpenseGrid($tr, $doctrine, $trip),
            'tripPointGrid'  => new TripPointGrid($tr, $doctrine, $trip),
            'editTripUserDialog' => EditTripUserDialog::create($formatter, $id),
            'editExpenseDialog' => EditExpenseDialog::create($formatter, $id),
            'editTripPointDialog' => EditTripPointDialog::create($formatter, $id),
        ];
    }

    /**
     * @Route("/detailData")
     */
    public function tripDetailGridAction() {
        $id = $this->getRequestNil()->getParam(Trip::COLUMN_ID);
        $trip = $this->getDoctrine()->getRepository('ProjBussinesTripBundle:Trip')->find($id);

        $paramList = new \GenericClass();
        $paramList->selfUser = $this->getSelfUser();
        $paramList->formatter = $this->getFormater();
        $paramList->translator = $this->getLangTranslator();
        $paramList->trip = $trip;

        TripUserGrid::renderDataDoctrine($this->getDoctrine(), $this->getRequestNil(), $paramList);
    }

    /**
     * @Route ("/editForm")
     * @Template()
     */
    public function editFormAction() {
        $id = $this->getRequestNil()->getParam(Trip::COLUMN_ID);
        $trip = new Trip();
        if ($id > 0) {
            $trip = $this->getDoctrine()->getRepository('ProjBussinesTripBundle:Trip')->find($id);
        }
        $trip = $trip ?: new Trip();
        $form = EditTripForm::create($this->getFormater(), $this->getRequestNil(), $this->getDoctrine(), $trip, $this->getSelfUser());
        return ['form' => $form];
    }

    /**
     * @Route ("/editTripUserForm")
     * @Template()
     */
    public function editTripUserFormAction() {
        $id = $this->getRequestNil()->getParam(TripUser::COLUMN_ID);
        $tripId = $this->getRequestNil()->getParam(EditTripUserForm::INPUT_TRIP);
        $tripUser = new TripUser();
        $trip = $this->getDoctrine()->getRepository('ProjBussinesTripBundle:Trip')->find($tripId);
        if ($id > 0) {
            $tripUser = $this->getDoctrine()->getRepository('ProjBussinesTripBundle:TripUser')->find($id);
        }
        $tripUser->setTrip($trip);
        $form = EditTripUserForm::create($this->getFormater(), $this->getRequestNil(), $this->getDoctrine(), $tripUser);
        return ['form' => $form];
    }

    /**
     * @Route("/grid")
     */
    public function gridAction() {
        $paramList = new \GenericClass();
        $paramList->selfUser = $this->getSelfUser();
        $paramList->formatter = $this->getFormater();
        $paramList->translator = $this->getLangTranslator();
        TripGrid::renderDataDoctrine($this->getDoctrine(), $this->getRequestNil(), $paramList);
    }

}