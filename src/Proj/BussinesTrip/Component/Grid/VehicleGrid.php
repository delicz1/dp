<?php
/**
 * @author springer
 */

namespace Proj\BussinesTrip\Component\Grid;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityRepository;
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

        $col = \GridColumn::create(self::COLUMN_OPTIONS, $t->(''));
        $col->option->fixed = true;
        $col->option->width = 80;


        $col = \GridColumn::create(self::COLUMN_ID, 'id');
        $col->option->sortable = true;
//        $col->option->searchoptions->sopt = [self::SOPT_EQUAL];
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

//        $col = \GridColumn::create(self::COLUMN_NAME, $t->get('user.name'));
//        $col->option->index = 'u.' . Vehicle::COLUMN_NAME;
//        $col->option->search = true;
//        $this->addColumnGrid($col);
//
//        $col = \GridColumn::create(self::COLUMN_SURNAME, $t->get('user.last.name'));
//        $col->option->index = 'u.' . Vehicle::COLUMN_SURNAME;
//        $col->option->search = true;
//        $this->addColumnGrid($col);

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
        return $doctrine->getRepository('ProjBaseBundle:Vehicle');
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
        $qb = $repository
            ->createQueryBuilder('v');
//            ->join('u.' . Vehicle::COLUMN_ROLES, 'r')
//            ->where('g.id = ' . $group->getId())
//            ->andWhere('BIT_AND(u.' . Vehicle::COLUMN_FLAG . ', :flag) = 0')
//            ->orderBy('u.id');
//            ->setParameters(['flag' => Vehicle::FLAG_DELETED]);
//        if (!$selfVehicle->can(Permission::ADMIN_ROLES)) {
//            $qb->andWhere(\Criteria::in('r.id', $selfVehicle->canIds(Permission::ADMIN_ROLES, new Role())));
//        }
        return $qb;
    }

    /**
     * @param \GridDataRender $gridDataRender
     * @param mixed           $paramList
     */
    public static function setRenders(\GridDataRender $gridDataRender, $paramList) {

        /** @noinspection PhpUnusedParameterInspection */
        $gridDataRender->addRender(self::COLUMN_ID, function ($user, $paramList) {
            /** @var Vehicle $user */
            return $user->getId();
        });

        /** @noinspection PhpUnusedParameterInspection */
        $gridDataRender->addRender(self::COLUMN_NAME, function ($vehicle, $paramList) {
            /** @var Vehicle $vehicle */
            return $vehicle->getName();
        });

//        $gridDataRender->addRender(self::COLUMN_ROLE, function ($user, $paramList) {
//            /** @var Vehicle $user */
//            /** @var Formatter $formater */
//            $formater = $paramList->formater;
//            $tr = $formater->getLangTranslator();
//            return $tr->get($user->getMainRole()->getTitle());
//        });
    }
}