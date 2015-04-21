<?php
/**
 * @author springer
 */

namespace Proj\BussinesTrip\Controller;


use Proj\BussinesTrip\Component\Form\EditTripPointForm;
use Proj\BussinesTrip\Component\Grid\TripPointGrid;
use Proj\BussinesTrip\Entity\TripPoint;
use /** @noinspection PhpUnusedAliasInspection */
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use /** @noinspection PhpUnusedAliasInspection */
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Proj\Base\Controller\BaseController;

/**
 * @Route("tripPoint", name="_tripPoint")
 * @Template()
 */
class TripPointController extends BaseController {

    const EDIT_FORM = '/tripPoint/editForm';
    const GRID_DATA = '/tripPoint/grid';


    /**
     * @Route ("/editForm")
     * @Template()
     */
    public function editFormAction() {
        $id = $this->getRequestNil()->getParam(TripPoint::COLUMN_ID);
        $tripId = $this->getRequestNil()->getParam(EditTripPointForm::PARAM_TRIP);
        $trip = $this->getDoctrine()->getRepository('ProjBussinesTripBundle:Trip')->find($tripId);
        $tripPoint = new TripPoint();
        if ($id > 0) {
            $tripPoint = $this->getDoctrine()->getRepository('ProjBussinesTripBundle:TripPoint')->find($id);
        }
        $tripPoint = $tripPoint ?: new TripPoint();
        $form = EditTripPointForm::create($this->getFormater(), $this->getRequestNil(), $this->getDoctrine(), $tripPoint, $trip);
        return ['form' => $form ];
    }

    /**
     * @Route("/grid")
     */
    public function gridAction() {
        $id = $this->getRequestNil()->getParam('id');
        $trip = $this->getDoctrine()->getRepository('ProjBussinesTripBundle:Trip')->find($id);
        $paramList = new \GenericClass();
        $paramList->selfUser = $this->getSelfUser();
        $paramList->formatter = $this->getFormater();
        $paramList->translator = $this->getLangTranslator();
        $paramList->trip = $trip;
        TripPointGrid::renderDataDoctrine($this->getDoctrine(), $this->getRequestNil(), $paramList);
    }

}