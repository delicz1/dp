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

class TripGrid {

    const IDENTIFIER = 'trip_grid';

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
            ->setCaption('Služební cesty')
            ->setColNames(
                ['id', 'Čas od', 'Čas do', 'Odkud', 'Kam', 'Počet míst'])
            ->setColModel([
                [
                    'name' => 'id', 'index' => 't.id', 'width' => 20,
                    'align' => 'left', 'sortable' => true, 'search' => false,
                ],
                [
                    'name' => 'timeFrom', 'index' => 't.time_from', 'width' => 50,
                    'align' => 'left', 'sortable' => true, 'search' => true,
                    'editable' => true, 'editrules' => ['required' => true]
                ],
                [
                    'name' => 'timeTo', 'index' => 't.time_to', 'width' => 50,
                    'align' => 'left', 'sortable' => true, 'search' => true,
                    'editable' => true, 'editrules' => ['required' => true],
                ],
                [
                    'name' => 'fromPoint', 'index' => 't.from_point', 'width' => 50,
                    'align' => 'left', 'sortable' => true, 'search' => true, 'editable' => true,
                ],
                [
                    'name' => 'toPoint', 'index' => 't.to_point', 'width' => 50, 'editable' => true,
                    'align' => 'left', 'sortable' => true, 'search' => true,
                    'editrules' => ['required' => true]
                ],
                [
                    'name' => 'capacity', 'index' => 't.capacity', 'width' => 50, 'editable' => true,
                    'align' => 'left', 'sortable' => true, 'search' => true,
                ]
            ])
            ->setHeight('auto')
            ->setQueryBuilder($this->getQueryBuilder())
            ->enableSearchButton(true)
            ->enableEditButton(true)
            ->enableAddButton(true)
            ->enableDeleteButton(true)
        ;

        return $dataGrid;
    }

    protected function getQueryBuilder()
    {
        $qb = $this->em->getRepository('AppBundle:Trip')->createQueryBuilder('t');
        $qb->select('t');
        return $qb;
    }

}