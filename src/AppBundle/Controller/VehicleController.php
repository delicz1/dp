<?php
/**
 * Created by PhpStorm.
 * User: springer
 * Date: 21.1.15
 * Time: 21:35
 */

namespace AppBundle\Controller;

use /** @noinspection PhpUnusedAliasInspection */
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Vehicle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class VehicleController extends Controller {


    /**
     * @Route("/app/vehicle/create", name="vehicleCreate")
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request) {
        $save = "";
        $vehicle = new Vehicle();

        $form = $this->createFormBuilder($vehicle)
            ->add('name', 'text', ["attr" => ["placeholder" => "povinné pole"], 'label' => 'Název'])
            ->add('numberPlate', 'text', ['label' => 'Registrační značka'])
            ->add('capacity', 'integer', ['label' => 'Počet lidí'])
            ->add('type', 'choice', [
                'label' => 'Typ dopravního prostředku',
                "choices" => [
                    1 => "Osobní auto",
                    2 => "Autobus",
                    3 => "Letadlo",
                ]])
            ->add('save', 'submit', array('label' => 'Uložit'))
            ->getForm();

        $form->handleRequest($request);
        $errors = $form->getErrors();
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            /** @var Vehicle $vehicle */
            $vehicle = $form->getData();
            $em->persist($vehicle);
            $save = "Uloženo !";
        }




        return $this->render('vehicle/create.html.twig', [
            'form' => $form->createView(),
            'saveStatus' => $save,
            'errors' => $errors
        ]);
    }
}