<?php
/**
 * @author springer
 */

namespace Proj\BussinesTrip\Controller;

use Proj\BussinesTrip\Component\Dialog\EditTripDialog;
use Proj\BussinesTrip\Component\Form\EditTripUserForm;
use Proj\BussinesTrip\Component\Form\EditTripForm;
use Proj\BussinesTrip\Component\Grid\TripGrid;
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

    const TRIP_DETAIL = '/trip/detail';
    const EDIT_FORM = '/trip/editForm';
    const EDIT_TRIP_USER_FORM = '/trip/editTripUserForm';
    const GRID_DATA = '/trip/grid';

    /**
     * @Route("/index")
     * @Template()
     */
    public function indexAction() {

        $formatter = $this->getFormater();
        $dialog = EditTripDialog::create($formatter);
        $grid = new TripGrid($this->getLangTranslator(), $this->getDoctrine());
        return ['grid' => $grid, 'dialog' => $dialog];
    }

    /**
     * @Route("/detail")
     * @Template()
     */
    public function tripDetailAction() {
        $id = $this->getRequestNil()->getParam(Trip::COLUMN_ID);
        $trip = $this->getDoctrine()->getRepository('ProjBussinesTripBundle:Trip')->find($id);
        return ['trip' => $trip, 'formatter' => $this->getFormater()];
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
        $form = EditTripForm::create($this->getFormater(), $this->getRequestNil(), $this->getDoctrine(), $trip);
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
        return ['form' => $form ];
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