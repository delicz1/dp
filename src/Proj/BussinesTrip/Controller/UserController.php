<?php
/**
 * @author springer
 */

namespace Proj\BussinesTrip\Controller;

use Proj\BussinesTrip\Component\Dialog\EditUserDialog;
use Proj\BussinesTrip\Component\Form\EditUserForm;
use Proj\BussinesTrip\Component\Grid\UserGrid;
use /** @noinspection PhpUnusedAliasInspection */
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use /** @noinspection PhpUnusedAliasInspection */
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Proj\Base\Controller\BaseController;

/**
 * @Route("/user", name="_user")
 * @Template()
 */
class UserController extends BaseController {

    const EDIT_FORM = '/user/editForm';
    const USER_GRID_DATA = '/user/grid';

    /**
     * @Route("/index")
     * @Template()
     */
    public function indexAction() {

        $formatter = $this->getFormater();
        $dialog = EditUserDialog::create($formatter);
        $test = 'testovani';
        $grid = new UserGrid($this->getLangTranslator(), $this->getDoctrine());
        return ['dialog' => $dialog, 'test' => $test, 'grid' => $grid];
    }

    /**
     * @Route ("/editForm")
     * @Template()
     */
    public function editFormAction() {
        $form = EditUserForm::create($this->getFormater(), $this->getRequestNil(), $this->getDoctrine());
        return ['form' => $form ];
    }

    /**
     * @Route("/grid")
     */
    public function gridAction() {
        $paramList = new \GenericClass();
        $paramList->selfUser = $this->getSelfUser();
        $paramList->formater = $this->getFormater();
        UserGrid::renderDataDoctrine($this->getDoctrine(), $this->getRequestNil(), $paramList);
    }

}