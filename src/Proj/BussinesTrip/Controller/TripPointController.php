<?php
/**
 * @author springer
 */

namespace Proj\BussinesTrip\Controller;


use Proj\BussinesTrip\Component\Dialog\EditVehicleDialog;
use Proj\BussinesTrip\Component\Form\EditExpenseForm;
use Proj\BussinesTrip\Component\Grid\ExpenseGrid;
use Proj\BussinesTrip\Component\Grid\VehicleGrid;
use Proj\BussinesTrip\Entity\Expense;
use Proj\BussinesTrip\Entity\TripPoint;
use Proj\BussinesTrip\Entity\Vehicle;
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
        $id = $this->getRequestNil()->getParam(Expense::COLUMN_ID);
        $tripId = $this->getRequestNil()->getParam(EditExpenseForm::PARAM_TRIP);
        $trip = $this->getDoctrine()->getRepository('ProjBussinesTripBundle:Trip')->find($tripId);
        $tripPoint = new TripPoint();
        if ($id > 0) {
            $tripPoint = $this->getDoctrine()->getRepository('ProjBussinesTripBundle:TripPoint')->find($id);
        }
        $tripPoint = $tripPoint ?: new Expense();
        $form = EditExpenseForm::create($this->getFormater(), $this->getRequestNil(), $this->getDoctrine(), $tripPoint, $trip);
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
        ExpenseGrid::renderDataDoctrine($this->getDoctrine(), $this->getRequestNil(), $paramList);
    }

}