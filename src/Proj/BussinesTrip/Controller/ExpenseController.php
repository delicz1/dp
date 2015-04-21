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
use Proj\BussinesTrip\Entity\Vehicle;
use /** @noinspection PhpUnusedAliasInspection */
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use /** @noinspection PhpUnusedAliasInspection */
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Proj\Base\Controller\BaseController;

/**
 * @Route("expense", name="_expense")
 * @Template()
 */
class ExpenseController extends BaseController {

    const EDIT_FORM = '/expense/editForm';
    const GRID_DATA = '/expense/grid';


    /**
     * @Route ("/editForm")
     * @Template()
     */
    public function editFormAction() {
        $id = $this->getRequestNil()->getParam(Expense::COLUMN_ID);
        $tripId = $this->getRequestNil()->getParam(EditExpenseForm::PARAM_TRIP);
        $trip = $this->getDoctrine()->getRepository('ProjBussinesTripBundle:Trip')->find($tripId);
        $expense = new Expense();
        if ($id > 0) {
            $expense = $this->getDoctrine()->getRepository('ProjBussinesTripBundle:Expense')->find($id);
        }
        $expense = $expense ?: new Expense();
        $form = EditExpenseForm::create($this->getFormater(), $this->getRequestNil(), $this->getDoctrine(), $expense, $trip);
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