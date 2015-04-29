<?php
/**
 * @author springer
 */

namespace Proj\BussinesTrip\Controller;


use Proj\BussinesTrip\Component\Dialog\EditVehicleDialog;
use Proj\BussinesTrip\Component\Form\EditVehicleForm;
use Proj\BussinesTrip\Component\Grid\VehicleGrid;
use Proj\BussinesTrip\Entity\Vehicle;
use /** @noinspection PhpUnusedAliasInspection */
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use /** @noinspection PhpUnusedAliasInspection */
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Proj\Base\Controller\BaseController;

/**
 * @Security("has_role('ROLE_ACCOUNTANT')")
 * @Route("vehicle", name="_vehicle")
 * @Template()
 */
class VehicleController extends BaseController {

    const EDIT_FORM = '/vehicle/editForm';
    const GRID_DATA = '/vehicle/grid';

    /**
     * @Route("/index")
     * @Template()
     */
    public function indexAction() {

        $formatter = $this->getFormater();
        $dialog = EditVehicleDialog::create($formatter);
        $grid = new VehicleGrid($this->getLangTranslator(), $this->getDoctrine());
        return ['grid' => $grid, 'dialog' => $dialog];
    }

    /**
     * @Route ("/editForm")
     * @Template()
     */
    public function editFormAction() {
        $id = $this->getRequestNil()->getParam(Vehicle::COLUMN_ID);
        $vehicle = new Vehicle();
        if ($id > 0) {
            $vehicle = $this->getDoctrine()->getRepository('ProjBussinesTripBundle:Vehicle')->find($id);
        }
        $vehicle = $vehicle ?: new Vehicle();
        $form = EditVehicleForm::create($this->getFormater(), $this->getRequestNil(), $this->getDoctrine(), $vehicle);
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
        VehicleGrid::renderDataDoctrine($this->getDoctrine(), $this->getRequestNil(), $paramList);
    }

}