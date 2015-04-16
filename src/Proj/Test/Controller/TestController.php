<?php
/**
 * @author necas
 */

namespace Proj\Test\Controller;

use Proj\Test\Component\Dialog\TestDialog;
use Proj\Test\Component\Form\AddUserForm;
use Proj\Test\Component\Grid\UserGrid;
use /** @noinspection PhpUnusedAliasInspection */
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use /** @noinspection PhpUnusedAliasInspection */
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Proj\Base\Controller\BaseController;

/**
 * @Route("", name="_test")
 * @Template()
 */
class TestController extends BaseController {


    const USER_GRID_DATA = '/grid';


    /**
     * @Route("/test")
     * @Template()
     */
    public function testAction() {

        $formatter = $this->getFormater();
        $dialog = TestDialog::create($formatter);
        $test = 'testovani';
        $grid = new UserGrid($this->getLangTranslator(), $this->getDoctrine());
        return ['dialog' => $dialog, 'test' => $test, 'grid' => $grid];
    }

    /**
     * @Route ("/testForm")
     * @Template()
     */
    public function testFormAction() {
        $form = AddUserForm::create($this->getFormater(), $this->getRequestNil(), $this->getDoctrine());
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