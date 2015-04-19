<?php
/**
 * @author springer
 */

namespace Proj\BussinesTrip\Controller;


use Proj\BussinesTrip\Component\Form\EditVehicleForm;
use Proj\BussinesTrip\Component\Grid\VehicleGrid;
use /** @noinspection PhpUnusedAliasInspection */
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use /** @noinspection PhpUnusedAliasInspection */
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Proj\Base\Controller\BaseController;

/**
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

//        $formatter = $this->getFormater();
//        $dialog = EditUserDialog::create($formatter);
        $grid = new VehicleGrid($this->getLangTranslator(), $this->getDoctrine());
        return ['grid' => $grid];
    }

    /**
     * @Route ("/editForm")
     * @Template()
     */
    public function editFormAction() {
        $form = EditVehicleForm::create($this->getFormater(), $this->getRequestNil(), $this->getDoctrine());
        return ['form' => $form ];
    }

    /**
     * @Route("/grid")
     */
    public function gridAction() {
        $paramList = new \GenericClass();
        $paramList->selfUser = $this->getSelfUser();
        $paramList->formater = $this->getFormater();
        VehicleGrid::renderDataDoctrine($this->getDoctrine(), $this->getRequestNil(), $paramList);
    }

}