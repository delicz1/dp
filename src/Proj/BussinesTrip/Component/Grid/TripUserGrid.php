<?php
/**
 * @author springer
 */

namespace Proj\BussinesTrip\Component\Grid;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityRepository;
use Proj\Base\Entity\User;
use Proj\Base\Object\Grid\GridAjaxDoctrine;
use Proj\Base\Object\Locale\LangTranslator;
use Proj\BussinesTrip\Component\Dialog\EditTripUserDialog;
use Proj\BussinesTrip\Controller\TripController;
use Proj\BussinesTrip\Controller\UserController;
use Proj\BussinesTrip\Entity\Trip;
use Proj\BussinesTrip\Entity\TripUser;

/**
 * Class UserGrid
 * @package Proj\BussinesTrip\Component\Grid
 */
class TripUserGrid extends GridAjaxDoctrine {

    //=====================================================
    //== Konstanty ========================================
    //=====================================================

    const COLUMN_OPTIONS        = 'options';
    const COLUMN_ID             = 'id';

    const ID        = 'tripuser_grid';
    const PAGER_ID  = 'trip_user_grid_pager';

    const URL = TripController::TRIP_DETAIL_DATA;

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


        $col = \GridColumn::create(User::COLUMN_EMAIL, $t->get('user.email'));
        $col->option->index = 'u.' . User::COLUMN_EMAIL;
        $col->option->search = true;
        $this->addColumnGrid($col);

        $col = \GridColumn::create(User::COLUMN_NAME, $t->get('user.name'));
        $col->option->index = 'u.' . User::COLUMN_NAME;
        $col->option->search = true;
        $this->addColumnGrid($col);

        $col = \GridColumn::create(User::COLUMN_SURNAME, $t->get('user.surname'));
        $col->option->index = 'u.' . User::COLUMN_SURNAME;
        $col->option->search = true;
        $this->addColumnGrid($col);

        $col = \GridColumn::create(TripUser::COLUMN_STATUS, $t->get('trip.status'));
        $col->option->index = 'tu.' . TripUser::COLUMN_STATUS;
        $col->option->sortable = true;
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
        return $doctrine->getRepository('ProjBussinesTripBundle:TripUser');
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
            ->createQueryBuilder('ut')
            ->join('ut.user', 'u')
            ->join('ut.trip', 't')
            ->where('t.id = ' . $trip->getId());
        return $qb;
    }

    /**
     * @param \GridDataRender $gridDataRender
     * @param mixed           $paramList
     */
    public static function setRenders(\GridDataRender $gridDataRender, $paramList) {

        /** @noinspection PhpUnusedParameterInspection */
        $gridDataRender->addRender(self::COLUMN_OPTIONS, function ($tripUser, $paramList) {
            /** @var TripUser $tripUser */
            /** @var LangTranslator $t */
            /** @var User $selfUser */
            $t = $paramList->translator;
            $selfUser = $paramList->selfUser;
            $buttons = '';
            if (! $selfUser->isRoleUser()) {
                $dialog = EditTripUserDialog::create($paramList->formatter, $tripUser->getTrip()->getId(), $tripUser->getId());
                $buttons = self::getEditButton($t, $dialog->render(false, false));
            }
                return $buttons;
        });

        /** @noinspection PhpUnusedParameterInspection */
        $gridDataRender->addRender(self::COLUMN_ID, function ($tripUser, $paramList) {
            /** @var TripUser $tripUser */
            return $tripUser->getId();
        });

        /** @noinspection PhpUnusedParameterInspection */
        $gridDataRender->addRender(User::COLUMN_EMAIL, function ($tripUser, $paramList) {
            /** @var TripUser $tripUser */
            return $tripUser->getUser()->getEmail();
        });

        /** @noinspection PhpUnusedParameterInspection */
        $gridDataRender->addRender(User::COLUMN_NAME, function ($tripUser, $paramList) {
            /** @var TripUser $tripUser */
            return $tripUser->getUser()->getName();
        });

        /** @noinspection PhpUnusedParameterInspection */
        $gridDataRender->addRender(User::COLUMN_SURNAME, function ($tripUser, $paramList) {
            /** @var TripUser $tripUser */
            return $tripUser->getUser()->getSurname();
        });

        /** @noinspection PhpUnusedParameterInspection */
        $gridDataRender->addRender(TripUser::COLUMN_STATUS, function ($tripUser, $paramList) {
            /** @var User $tripUser */
            /** @var LangTranslator $t */
            $t = $paramList->translator;
            $text = TripUser::$statusList[$tripUser->getStatus()];
            return $t->get($text);
        });
    }
}