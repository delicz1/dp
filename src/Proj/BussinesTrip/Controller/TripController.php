<?php
/**
 * @author springer
 */

namespace Proj\BussinesTrip\Controller;

use Proj\BussinesTrip\Component\Dialog\EditTripDialog;
use Proj\BussinesTrip\Component\Form\EditTripForm;
use Proj\BussinesTrip\Component\Grid\TripGrid;
use Proj\BussinesTrip\Entity\Trip;
use Proj\BussinesTrip\Entity\Vehicle;
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

    const EDIT_FORM = '/trip/editForm';
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
     * @Route ("/editForm")
     * @Template()
     */
    public function editFormAction() {
        $id = $this->getRequestNil()->getParam(Vehicle::COLUMN_ID);
        $trip = new Trip();
        if ($id > 0) {
            $trip = $this->getDoctrine()->getRepository('ProjBussinesTripBundle:Trip')->find($id);
        }
        $trip = $trip ?: new Trip();
        $form = EditTripForm::create($this->getFormater(), $this->getRequestNil(), $this->getDoctrine(), $trip);
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