<?php
/**
 * Created by PhpStorm.
 * User: springer
 * Date: 21.1.15
 * Time: 21:35
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Vehicle;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class VehicleController extends Controller {

    /**
     * @Route("/app/vehicle/index", name="vehicleList")
     */
    public function indexAction() {
        return "test";
    }

    /**
     * @Route("/app/vehicle/create", name="vehicleCreate")
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request) {
        // create a task and give it some dummy data for this example
        $save = "";
        $vehicle = new Vehicle();
        $vehicle->setName('Pojmenovani auta');
        $vehicle->setNumberPlate("Registracni znacka");

        $form = $this->createFormBuilder($vehicle)
            ->add('name', 'text')
            ->add('numberPlate', 'text')
            ->add('save', 'submit', array('label' => 'Vytvor auto'))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();
            $save = "Ulozeno !";
        }




        return $this->render('vehicle/create.html.twig', array(
            'form' => $form->createView(), 'saveStatus' => $save
        ));
    }
}