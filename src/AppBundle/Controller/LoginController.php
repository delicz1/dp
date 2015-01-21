<?php
/**
 * Created by PhpStorm.
 * User: springer
 * Date: 21.1.15
 * Time: 19:04
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LoginController extends Controller {

    /**
     * @Route("/app/login", name="loginPage")
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }
}