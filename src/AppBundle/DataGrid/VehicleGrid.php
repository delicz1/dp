<?php
/**
 * Created by PhpStorm.
 * User: springer
 * Date: 31.1.15
 * Time: 5:01
 */

namespace AppBundle\DataGrid;

use AppBundle\Entity\Vehicle;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Thrace\DataGridBundle\DataGrid\DataGridFactoryInterface;
use Thrace\DataGridBundle\DataGrid\DataGridInterface;

class VehicleGrid {

    const IDENTIFIER = 'vehicle_grid';

    protected $factory;

    protected $translator;

    protected $router;

    protected $em;


    public function __construct (DataGridFactoryInterface $factory, TranslatorInterface $translator, RouterInterface $router,
                                 EntityManager $em)
    {
        $this->factory = $factory;
        $this->translator = $translator;
        $this->router = $router;
        $this->em = $em;
    }

    public function build ()
    {

        /** @var  DataGridInterface $dataGrid */
        $dataGrid = $this->factory->createDataGrid(self::IDENTIFIER);
        $dataGrid
            ->setCaption('JsGrid')
            ->setColNames(
                ['id', 'Vozidlo', 'Registrační značka', 'Typ dopravniho prostředku', 'Kapacita'])
            ->setColModel([
                [
                    'name' => 'id', 'index' => 'v.id', 'width' => 20,
                    'align' => 'left', 'sortable' => true, 'search' => true,
                ],
                [
                    'name' => 'name', 'index' => 'v.name', 'width' => 50,
                    'align' => 'left', 'sortable' => true, 'search' => true,
                ],
                [
                    'name' => 'numberPlate', 'index' => 'v.number_plate', 'width' => 50,
                    'align' => 'left', 'sortable' => true, 'search' => true,
                ],
                [
                    'name' => 'type', 'index' => 'v.type', 'width' => 50,
                    'align' => 'left', 'sortable' => true, 'search' => true,
                    'formatter' => self::getVehicleTypeJs(),
                    'stype' => 'select','searchoptions' => [
                        'value' => Vehicle::$typeList,
                        'sopt' => ['eq']
                    ]
                ],
                [
                    'name' => 'capacity', 'index' => 'v.capacity', 'width' => 50,
                    'align' => 'left', 'sortable' => true, 'search' => true,
                ]
            ])
            ->setHeight('auto')
            ->setQueryBuilder($this->getQueryBuilder())
            ->enableSearchButton(true)
            ->enableEditButton(true)->setEditBtnUri($this->router->generate('vehicleEdit'))
            ->enableAddButton(true)
            ->enableDeleteButton(true)
        ;

        return $dataGrid;
    }


    public static function getVehicleTypeJs() {
        $js = "function( cellvalue, options, rowObject ) {\n";
        $js .= "var type = '';\n";
        $js .= "switch(cellvalue) {";
        foreach (Vehicle::$typeList as $key => $val) {
            $js .= "case $key: var type='$val'; break;\n";
        }
        $js .= "}\n";
        $js .= "return type; }\n";
        return $js;
    }

    protected function getQueryBuilder()
    {
        $qb = $this->em->getRepository('AppBundle:Vehicle')->createQueryBuilder('v');
        $qb->select('v');
        return $qb;
    }

}