<?php
/**
 * @author springer
 */

namespace Proj\BussinesTrip\Controller;

use Proj\Base\Entity\User;
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
        $id = $this->getRequestNil()->getParam('id');
        $user = new User();
        if ($id > 0) {
            $user = $this->getDoctrine()->getRepository('ProjBaseBundle:User')->find($id);
        }
        $form = EditUserForm::create($this->getFormater(), $this->getRequestNil(), $this->getDoctrine(), $user, $this->getSelfUser());
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
        UserGrid::renderDataDoctrine($this->getDoctrine(), $this->getRequestNil(), $paramList);
    }

}