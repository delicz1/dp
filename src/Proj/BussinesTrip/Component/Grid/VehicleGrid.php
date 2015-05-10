<?php
/**
 * @author springer
 */

namespace Proj\BussinesTrip\Component\Grid;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityRepository;
use Proj\BussinesTrip\Component\Dialog\EditVehicleDialog;
use Proj\BussinesTrip\Entity\Vehicle;
use Proj\Base\Object\Grid\GridAjaxDoctrine;
use Proj\Base\Object\Locale\LangTranslator;
use Proj\BussinesTrip\Controller\VehicleController;

/**
 * Class VehicleGrid
 * @package Proj\BussinesTrip\Component\Grid
 */
class VehicleGrid extends GridAjaxDoctrine {

    //=====================================================
    //== Konstanty ========================================
    //=====================================================

    const COLUMN_OPTIONS        = 'options';
    const COLUMN_ID             = 'id';

    const ID        = 'vehicle_grid';
    const PAGER_ID  = 'vehicle_grid_pager';

    const URL = VehicleController::GRID_DATA;

    //=====================================================
    //== Vnorene objekty ==================================
    //=====================================================

    /**
     * @var \Doctrine\Bundle\DoctrineBundle\Registry
     */
    private $doctrine;

    //=====================================================
    //== Konstruktor ======================================
    //=====================================================

    /**
     * @param null|string $translator
     * @param Registry $doctrine
     */
    public function __construct($translator, Registry $doctrine) {
        $this->translator = $translator;
        $this->doctrine = $doctrine;
        parent::__construct(self::URL . '?hak=1');
    }

    //=====================================================
    //== Verejne metody ===================================
    //=====================================================

    public function options() {
        $this->soption->type = self::TYPE_HTML;
    }

    public function settings() {
        $this->filterToolbar(true);
    }


    public function columns() {
        /** @var LangTranslator $t */
        $t = $this->translator;

        $col = \GridColumn::create(self::COLUMN_OPTIONS, $t->get('grid.options'));
        $col->option->fixed = true;
        $col->option->width = 80;
        $this->addColumnGrid($col);


        $col = \GridColumn::create(self::COLUMN_ID, 'id');
        $col->option->sortable = true;
        $col->option->index = 'v.id';
        $col->option->fixed = true;
        $col->option->width = 80;

        $this->addColumnGrid($col);


        $col = \GridColumn::create(Vehicle::COLUMN_NAME, $t->get('vehicle.name'));
        $col->option->index = 'v.' . Vehicle::COLUMN_NAME;
        $col->option->search = true;
        $this->addColumnGrid($col);

        $col = \GridColumn::create(Vehicle::COLUMN_TYPE, $t->get('vehicle.type'));
        $col->option->index = 'v.' . Vehicle::COLUMN_TYPE;
        $this->addColumnGrid($col);

        $col = \GridColumn::create(Vehicle::COLUMN_CAPACITY, $t->get('vehicle.capacity'));
        $col->option->index = 'v.' . Vehicle::COLUMN_CAPACITY;
        $col->option->sortable = true;
        $col->option->search = true;
        $this->addColumnGrid($col);

//        $valueList = [Vehicle::STATUS_ACTIVE . ":" . $t->get(Vehicle::STATUS_ACTIVE_TRANS),
//                      Vehicle::STATUS_DELETED. ":" . $t->get(Vehicle::STATUS_DELETED_TRANS), ];

        $col = \GridColumn::create(Vehicle::COLUMN_STATUS, $t->get('vehicle.status'));
        $col->option->index = 'v.' . Vehicle::COLUMN_STATUS;
        $col->option->sortable = true;

//        $options = new \GenericClass();
//        $options->value = ":-- " . $t->get('grid.all') . " --;" . implode(';', $valueList);
//        $options->separator = ":";
//        $options->delimiter = ";";
//        $options->sopt = [GridAjaxDoctrine::SOPT_EQUAL];
//        $options->defaultValue = '';
//        $col->option->searchoptions = $options;
        $this->addColumnGrid($col);
    }

    //=====================================================
    //== Verejne staticke metody ==========================
    //=====================================================

    /**
     * @param \Doctrine\Bundle\DoctrineBundle\Registry $doctrine
     * @param $paramList
     * @param $gridFilter
     * @return null|string
     */
    public static function getRepository(Registry $doctrine, $paramList, $gridFilter) {
        return $doctrine->getRepository('ProjBussinesTripBundle:Vehicle');
    }

    /**
     * @param \Doctrine\ORM\EntityRepository $repository
     * @param \Doctrine\Bundle\DoctrineBundle\Registry $doctrine
     * @param $paramList
     * @param $gridFilter
     * @return \Doctrine\ORM\QueryBuilder|null
     */
    public static function getQueryBuilder(EntityRepository $repository, Registry $doctrine, $paramList, $gridFilter) {
        /** @var Vehicle $selfVehicle */
//        $selfVehicle = $paramList->selfVehicle;
        /** @var \Doctrine\ORM\QueryBuilder $qb */
        $qb = $repository->createQueryBuilder('v');
        return $qb;
    }

    /**
     * @param \GridDataRender $gridDataRender
     * @param mixed           $paramList
     */
    public static function setRenders(\GridDataRender $gridDataRender, $paramList) {

        /** @noinspection PhpUnusedParameterInspection */
        $gridDataRender->addRender(self::COLUMN_OPTIONS, function ($vehicle, $paramList) {
            /** @var Vehicle $vehicle */
            /** @var LangTranslator $t */
            $t = $paramList->translator;
            $dialog = EditVehicleDialog::create($paramList->formatter, $vehicle->getId());
            return self::getEditButton($t, $dialog->render(false, false));
        });


        /** @noinspection PhpUnusedParameterInspection */
        $gridDataRender->addRender(self::COLUMN_ID, function ($vehicle, $paramList) {
            /** @var Vehicle $vehicle */
            return $vehicle->getId();
        });

        /** @noinspection PhpUnusedParameterInspection */
        $gridDataRender->addRender(Vehicle::COLUMN_NAME, function ($vehicle, $paramList) {
            /** @var Vehicle $vehicle */
            return $vehicle->getName();
        });

        /** @noinspection PhpUnusedParameterInspection */
        $gridDataRender->addRender(Vehicle::COLUMN_TYPE, function ($vehicle, $paramList) {
            /** @var Vehicle $vehicle */
            /** @var LangTranslator $t */
            $t = $paramList->translator;
            $typeTr = Vehicle::$typeList[$vehicle->getType()];
            return $t->get($typeTr);
        });

        /** @noinspection PhpUnusedParameterInspection */
        $gridDataRender->addRender(Vehicle::COLUMN_CAPACITY, function ($vehicle, $paramList) {
            /** @var Vehicle $vehicle */
            return $vehicle->getCapacity();
        });

        /** @noinspection PhpUnusedParameterInspection */
        $gridDataRender->addRender(Vehicle::COLUMN_STATUS, function ($vehicle, $paramList) {
            /** @var Vehicle $vehicle */
            /** @var LangTranslator $t */
            $t = $paramList->translator;
            $text = Vehicle::$statusList[$vehicle->getStatus()];
            return $t->get($text);
        });
    }
}