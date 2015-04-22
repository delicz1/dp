<?php
/**
 * @author springer
 */

namespace Proj\BussinesTrip\Component\Grid;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityRepository;
use Proj\Base\Object\Locale\Formatter;
use Proj\BussinesTrip\Component\Dialog\EditTripDialog;
use Proj\BussinesTrip\Component\Dialog\EditTripUserDialog;
use Proj\BussinesTrip\Controller\TripController;
use Proj\BussinesTrip\Entity\Trip;
use Proj\BussinesTrip\Entity\Vehicle;
use Proj\Base\Object\Grid\GridAjaxDoctrine;
use Proj\Base\Object\Locale\LangTranslator;

/**
 * Class TripGrid
 * @package Proj\BussinesTrip\Component\Grid
 */
class TripGrid extends GridAjaxDoctrine {

    //=====================================================
    //== Konstanty ========================================
    //=====================================================

    const COLUMN_OPTIONS        = 'options';
    const COLUMN_ID             = 'id';

    const ID        = 'trip_grid';
    const PAGER_ID  = 'trip_grid_pager';

    const URL = TripController::GRID_DATA;

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
        $col->option->index = 't.id';
        $col->option->fixed = true;
        $col->option->width = 80;
        $this->addColumnGrid($col);

        $col = \GridColumn::create(Vehicle::COLUMN_NAME, $t->get('vehicle.vehicle'));
        $col->option->index = 'v.' . Vehicle::COLUMN_NAME;
        $col->option->search = true;
        $this->addColumnGrid($col);

        $col = \GridColumn::create(Trip::COLUMN_TIME_FROM, $t->get('trip.time.from'));
        $col->option->index = 't.' . Trip::COLUMN_TIME_FROM;
        $col->option->sortable = true;
        $this->addColumnGrid($col);

        $col = \GridColumn::create(Trip::COLUMN_TIME_TO, $t->get('trip.time.to'));
        $col->option->index = 't.' . Trip::COLUMN_TIME_TO;
        $col->option->sortable = true;
        $this->addColumnGrid($col);

        $col = \GridColumn::create(Trip::COLUMN_POINT_FROM, $t->get('trip.point.from'));
        $col->option->index = 't.' . Trip::COLUMN_POINT_FROM;
        $col->option->search = true;
        $this->addColumnGrid($col);

        $col = \GridColumn::create(Trip::COLUMN_POINT_TO, $t->get('trip.point.to'));
        $col->option->index = 't.' . Trip::COLUMN_POINT_TO;
        $col->option->search = true;
        $this->addColumnGrid($col);

        $col = \GridColumn::create(Trip::COLUMN_DISTANCE, $t->get('trip.distance'));
        $col->option->index = 't.' . Trip::COLUMN_DISTANCE;
        $col->option->search = true;
        $this->addColumnGrid($col);

        $col = \GridColumn::create(Trip::COLUMN_PURPOSE, $t->get('trip.purpose'));
        $col->option->index = 't.' . Trip::COLUMN_PURPOSE;
        $col->option->search = true;
        $this->addColumnGrid($col);

        $col = \GridColumn::create(Trip::COLUMN_STATUS, $t->get('trip.status'));
        $col->option->index = 't.' . Trip::COLUMN_STATUS;
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
        return $doctrine->getRepository('ProjBussinesTripBundle:Trip');
    }

    /**
     * @param \Doctrine\ORM\EntityRepository $repository
     * @param \Doctrine\Bundle\DoctrineBundle\Registry $doctrine
     * @param $paramList
     * @param $gridFilter
     * @return \Doctrine\ORM\QueryBuilder|null
     */
    public static function getQueryBuilder(EntityRepository $repository, Registry $doctrine, $paramList, $gridFilter) {
        /** @var \Doctrine\ORM\QueryBuilder $qb */
        $qb = $repository->createQueryBuilder('t');
        $qb->join('t.vehicle', 'v');
        return $qb;
    }

    /**
     * @param \GridDataRender $gridDataRender
     * @param mixed           $paramList
     */
    public static function setRenders(\GridDataRender $gridDataRender, $paramList) {

        /** @noinspection PhpUnusedParameterInspection */
        $gridDataRender->addRender(self::COLUMN_OPTIONS, function ($trip, $paramList) {
            /** @var Trip $trip */
            /** @var LangTranslator $t */
            $t = $paramList->translator;
            $dialog = EditTripDialog::create($paramList->formatter, $trip->getId());
            $buttons = self::getEditButton($t, $dialog->render(false, false));

            $dialog = EditTripUserDialog::create($paramList->formatter, $trip->getId(), null);
            $buttons .= self::getEditButton($t, $dialog->render(false, false));

            $buttons .= self::getEditButton($t, "window.location='".TripController::TRIP_DETAIL . '?id=' . $trip->getId() . "'");
            return $buttons;
        });


        /** @noinspection PhpUnusedParameterInspection */
        $gridDataRender->addRender(self::COLUMN_ID, function ($trip, $paramList) {
            /** @var Trip $trip */
            return $trip->getId();
        });

        /** @noinspection PhpUnusedParameterInspection */
        $gridDataRender->addRender(Vehicle::COLUMN_NAME, function (Trip $trip, $paramList) {
            return $trip->getVehicle()->getName();
        });

        /** @noinspection PhpUnusedParameterInspection */
        $gridDataRender->addRender(Trip::COLUMN_TIME_FROM, function (Trip $trip, $paramList) {
            /** @var Formatter $formatter */
            $formatter = $paramList->formatter;
            return $formatter->timestamp($trip->getTimeFrom(), Formatter::FORMAT_DATE_TIME);
        });

        /** @noinspection PhpUnusedParameterInspection */
        $gridDataRender->addRender(Trip::COLUMN_TIME_TO, function (Trip $trip, $paramList) {
            /** @var Formatter $formatter */
            $formatter = $paramList->formatter;
            return $formatter->timestamp($trip->getTimeTo(), Formatter::FORMAT_DATE_TIME);
        });

        /** @noinspection PhpUnusedParameterInspection */
        $gridDataRender->addRender(Trip::COLUMN_POINT_FROM, function (Trip $trip, $paramList) {
            return $trip->getPointFrom();
        });

        /** @noinspection PhpUnusedParameterInspection */
        $gridDataRender->addRender(Trip::COLUMN_POINT_TO, function (Trip $trip, $paramList) {
            return $trip->getPointTo();
        });

        /** @noinspection PhpUnusedParameterInspection */
        $gridDataRender->addRender(Trip::COLUMN_DISTANCE, function (Trip $trip, $paramList) {
            return $trip->getDistance();
        });

        /** @noinspection PhpUnusedParameterInspection */
        $gridDataRender->addRender(Trip::COLUMN_PURPOSE, function (Trip $trip, $paramList) {
            return $trip->getPurpose();
        });

        /** @noinspection PhpUnusedParameterInspection */
        $gridDataRender->addRender(Trip::COLUMN_STATUS, function (Trip $trip, $paramList) {
            /** @var LangTranslator $t */
            $t = $paramList->translator;
            $text = Trip::$statusList[$trip->getStatus()];
            return $t->get($text);
        });
    }
}