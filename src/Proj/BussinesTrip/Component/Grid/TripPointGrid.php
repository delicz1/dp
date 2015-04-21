<?php
/**
 * @author springer
 */

namespace Proj\BussinesTrip\Component\Grid;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityRepository;
use Proj\Base\Object\Grid\GridAjaxDoctrine;
use Proj\Base\Object\Locale\Formatter;
use Proj\Base\Object\Locale\LangTranslator;
use Proj\BussinesTrip\Component\Dialog\EditTripPointDialog;
use Proj\BussinesTrip\Controller\TripPointController;
use Proj\BussinesTrip\Entity\Trip;
use Proj\BussinesTrip\Entity\TripPoint;

/**
 * Class TripPointGrid
 * @package Proj\BussinesTrip\Component\Grid
 */
class TripPointGrid extends GridAjaxDoctrine {

    //=====================================================
    //== Konstanty ========================================
    //=====================================================

    const COLUMN_OPTIONS        = 'options';
    const COLUMN_ID             = 'id';

    const ID        = 'trip_point_grid';
    const PAGER_ID  = 'trip_point_grid_pager';

    const URL = TripPointController::GRID_DATA;

    //=====================================================
    //== Vnorene objekty ==================================
    //=====================================================

    /**
     * @var \Doctrine\Bundle\DoctrineBundle\Registry
     */
    private $doctrine;

    /**
     * @var Trip
     */
    private $trip;

    //=====================================================
    //== Konstruktor ======================================
    //=====================================================

    /**
     * @param null|string $translator
     * @param Registry    $doctrine
     * @param Trip        $trip
     */
    public function __construct($translator, Registry $doctrine, Trip $trip) {
        $this->translator = $translator;
        $this->doctrine = $doctrine;
        $this->trip = $trip;
        parent::__construct(self::URL . '?hak=1&id='. $trip->getId());
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
//        $col->option->searchoptions->sopt = [self::SOPT_EQUAL];
        $col->option->index = 'u.id';
        $col->option->fixed = true;
        $col->option->width = 80;

        $this->addColumnGrid($col);


        $col = \GridColumn::create(TripPoint::COLUMN_POINT, $t->get('trip.point.point'));
        $col->option->index = 'tp.' . TripPoint::COLUMN_POINT;
        $col->option->search = true;
        $this->addColumnGrid($col);

        $col = \GridColumn::create(TripPoint::COLUMN_TIME_FROM, $t->get('trip.point.time.from'));
        $col->option->index = 'tp.' . TripPoint::COLUMN_TIME_TO;
        $this->addColumnGrid($col);

        $col = \GridColumn::create(TripPoint::COLUMN_TIME_TO, $t->get('trip.point.time.to'));
        $col->option->index = 'tp.' . TripPoint::COLUMN_TIME_TO;
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
        return $doctrine->getRepository('ProjBussinesTripBundle:TripPoint');
    }

    /**
     * @param \Doctrine\ORM\EntityRepository $repository
     * @param \Doctrine\Bundle\DoctrineBundle\Registry $doctrine
     * @param $paramList
     * @param $gridFilter
     * @return \Doctrine\ORM\QueryBuilder|null
     */
    public static function getQueryBuilder(EntityRepository $repository, Registry $doctrine, $paramList, $gridFilter) {
        /** @var Trip $trip */
        $trip = $paramList->trip;
        /** @var \Doctrine\ORM\QueryBuilder $qb */
        $qb = $repository
            ->createQueryBuilder('tp')
            ->join('tp.trip', 't')
            ->where('t.id = ' . $trip->getId());
        return $qb;
    }

    /**
     * @param \GridDataRender $gridDataRender
     * @param mixed           $paramList
     */
    public static function setRenders(\GridDataRender $gridDataRender, $paramList) {

        /** @noinspection PhpUnusedParameterInspection */
        $gridDataRender->addRender(self::COLUMN_OPTIONS, function ($tripPoint, $paramList) {
            /** @var TripPoint $tripPoint */
            /** @var LangTranslator $t */
            $t = $paramList->translator;
            $dialog = EditTripPointDialog::create($paramList->formatter, $tripPoint->getTrip()->getId(), $tripPoint->getId());
            return self::getEditButton($t, $dialog->render(false, false));
        });

        /** @noinspection PhpUnusedParameterInspection */
        $gridDataRender->addRender(self::COLUMN_ID, function ($tripPoint, $paramList) {
            /** @var TripPoint $tripPoint */
            return $tripPoint->getId();
        });

        /** @noinspection PhpUnusedParameterInspection */
        $gridDataRender->addRender(TripPoint::COLUMN_POINT, function ($tripPoint, $paramList) {
            /** @var TripPoint $tripPoint */
            return $tripPoint->getPoint();
        });

        /** @noinspection PhpUnusedParameterInspection */
        $gridDataRender->addRender(TripPoint::COLUMN_TIME_FROM, function ($tripPoint, $paramList) {
            /** @var TripPoint $tripPoint */
            /** @var Formatter $formatter */
            $formatter = $paramList->formatter;
            return $formatter->timestamp($tripPoint->getTimeFrom(), Formatter::FORMAT_DATE_TIME);
        });

        /** @noinspection PhpUnusedParameterInspection */
        $gridDataRender->addRender(TripPoint::COLUMN_TIME_TO, function ($tripPoint, $paramList) {
            /** @var TripPoint $tripPoint */
            /** @var Formatter $formatter */
            $formatter = $paramList->formatter;
            return $formatter->timestamp($tripPoint->getTimeTo(), Formatter::FORMAT_DATE_TIME);
        });
    }
}