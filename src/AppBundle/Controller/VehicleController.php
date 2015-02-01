<?php
/**
 * Created by PhpStorm.
 * User: springer
 * Date: 21.1.15
 * Time: 21:35
 */

namespace AppBundle\Controller;

use Doctrine\ORM\AbstractQuery;
use Kitpages\DataGridBundle\Grid\Field;
use Kitpages\DataGridBundle\Grid\GridConfig;
use /** @noinspection PhpUnusedAliasInspection */
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Vehicle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class VehicleController extends Controller {


    /**
     * @Route("/app/vehicle/index", name="vehicleIndex")
     * @return Response
     */
    public function indexAction() {
//        /** @var \Thrace\DataGridBundle\DataGrid\DataGridInterface */
//        $vehicleGrid = $this->container->get('thrace_data_grid.provider')->get('vehicle_grid');
//
        return $this->render('vehicle/list.html3.twig');
    }

    /**
     * @Route("/app/vehicle/list", name="vehicleList")
     * @param Request $request
     * @return Response
     */
    public function vehicleListAction(Request $request) {

        $em    = $this->get('doctrine.orm.entity_manager');
        /** @var AbstractQuery $dql */
        $dql   = $this->getDoctrine()->getRepository('AppBundle:Vehicle');
        /** @noinspection PhpUndefinedMethodInspection */
        $query = $dql->createQueryBuilder('a');

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1)/*page number*/,
            5/*limit per page*/
        );

        // parameters to template
        return $this->render('vehicle/list.html.twig', array('pagination' => $pagination));

    }

    /**
     * @Route("/app/vehicle/list2", name="vehicleList2")
     * @param Request $request
     * @return Response
     */
    public function vehicleList2Action(Request $request) {

        // create query builder
        $repository = $this->getDoctrine()->getRepository('AppBundle:Vehicle');
        $queryBuilder = $repository->createQueryBuilder('item');

        $gridConfig = new GridConfig();
        $gridConfig
            ->setQueryBuilder($queryBuilder)
            ->setCountFieldName('item.id')
            ->addField(new Field('item.id'))
            ->addField(new Field('item.name', array('filterable' => true)))
        ;

        $gridManager = $this->get('kitpages_data_grid.grid_manager');
        $grid = $gridManager->getGrid($gridConfig, $request);

        return $this->render('vehicle/list.html2.twig', array(
            'grid' => $grid
        ));
    }


    /**
     * @Route("/app/vehicle/edit", name="vehicleEdit")
     * @param Request $request
     */
    public function editAction(Request $request) {
            var_dump($request);
//        $id = $request->get('id');
//        $repository = $this->getDoctrine()->getRepository('AppBundle:Vehicle');
//        /** @var Vehicle $vehicle */
//        $vehicle = $repository->find($id);
//        var_dump($vehicle);
        exit;
    }

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