<?php
/**
 * Created by PhpStorm.
 * User: springer
 * Date: 21.1.15
 * Time: 19:04
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Trip;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TripController extends Controller {

    /**
     * @Route("/app/trip/index", name="tripIndex")
     */
    public function indexAction()
    {
        return $this->render('trip/grid.html.twig');
    }

    /**
     * @Route("/app/trip/create", name="tripCreate")
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request)
    {
        $trip = new Trip();
        $save = false;
        $form = $this->createFormBuilder($trip)
            ->add('timeFrom', 'collot_datetime', ['label' => 'Čas od'])
            ->add('timeTo', 'collot_datetime', ['label' => 'Čas do'])
            ->add('save', 'submit', array('label' => 'Uložit'))
            ->getForm();

        $form->handleRequest($request);
        $errors = $form->getErrors();
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            /** @var Trip $trip */
            $trip = $form->getData();
            $em->persist($trip);
            $save = true;
        }
        return $this->render('trip/create.html.twig', [
            'form' => $form->createView(),
            'saveStatus' => $save,
            'errors' => $errors
        ]);
    }
}