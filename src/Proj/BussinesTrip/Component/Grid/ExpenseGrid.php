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
use Proj\BussinesTrip\Component\Dialog\EditExpenseDialog;
use Proj\BussinesTrip\Controller\ExpenseController;
use Proj\BussinesTrip\Entity\Expense;
use Proj\BussinesTrip\Entity\Trip;

/**
 * Class ExpenseGrid
 * @package Proj\BussinesTrip\Component\Grid
 */
class ExpenseGrid extends GridAjaxDoctrine {

    //=====================================================
    //== Konstanty ========================================
    //=====================================================

    const COLUMN_OPTIONS        = 'options';
    const COLUMN_ID             = 'id';

    const ID        = 'expense_grid';
    const PAGER_ID  = 'expense_grid_pager';

    const URL = ExpenseController::GRID_DATA;

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

        $col = \GridColumn::create(Expense::COLUMN_TYPE, $t->get('expense.type'));
        $col->option->index = 'e.' . Expense::COLUMN_TYPE;
        $col->option->search = true;
        $this->addColumnGrid($col);

        $col = \GridColumn::create(Expense::COLUMN_PRICE, $t->get('expense.price'));
        $col->option->index = 'e.' . Expense::COLUMN_PRICE;
        $col->option->search = true;
        $this->addColumnGrid($col);

        $col = \GridColumn::create(Expense::COLUMN_CURRENCY, $t->get('expense.currency'));
        $col->option->index = 'e.' . Expense::COLUMN_CURRENCY;
        $col->option->search = true;
        $this->addColumnGrid($col);

        $col = \GridColumn::create(Expense::COLUMN_DESCRIPTION, $t->get('expense.description'));
        $col->option->index = 'e.' . Expense::COLUMN_DESCRIPTION;
        $col->option->search = true;
        $this->addColumnGrid($col);


        $col = \GridColumn::create(Expense::COLUMN_STATUS, $t->get('expense.status'));
        $col->option->index = 'e.' . Expense::COLUMN_STATUS;
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
        return $doctrine->getRepository('ProjBussinesTripBundle:Expense');
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
            ->createQueryBuilder('e')
            ->join('e.tripUser', 'tu')
            ->join('tu.trip', 't')
            ->join('tu.user', 'u')
            ->where('t.id = ' . $trip->getId());
        return $qb;
    }

    /**
     * @param \GridDataRender $gridDataRender
     * @param mixed           $paramList
     */
    public static function setRenders(\GridDataRender $gridDataRender, $paramList) {

        /** @noinspection PhpUnusedParameterInspection */
        $gridDataRender->addRender(self::COLUMN_OPTIONS, function ($expense, $paramList) {
            /** @var Expense $expense */
            /** @var LangTranslator $t */
            $t = $paramList->translator;
            $dialog = EditExpenseDialog::create($paramList->formatter, $expense->getTripUser()->getTrip()->getId(), $expense->getId());
            return self::getEditButton($t, $dialog->render(false, false));
        });

        /** @noinspection PhpUnusedParameterInspection */
        $gridDataRender->addRender(self::COLUMN_ID, function ($expense, $paramList) {
            /** @var Expense $expense */
            return $expense->getId();
        });

        /** @noinspection PhpUnusedParameterInspection */
        $gridDataRender->addRender(User::COLUMN_EMAIL, function ($expense, $paramList) {
            /** @var Expense $expense */
            return $expense->getTripUser()->getUser()->getEmail();
        });

        /** @noinspection PhpUnusedParameterInspection */
        $gridDataRender->addRender(User::COLUMN_NAME, function ($expense, $paramList) {
            /** @var Expense $expense */
            return $expense->getTripUser()->getUser()->getName();
        });

        /** @noinspection PhpUnusedParameterInspection */
        $gridDataRender->addRender(User::COLUMN_SURNAME, function ($expense, $paramList) {
            /** @var Expense $expense */
            return $expense->getTripUser()->getUser()->getSurname();
        });

        /** @noinspection PhpUnusedParameterInspection */
        $gridDataRender->addRender(Expense::COLUMN_TYPE, function ($expense, $paramList) {
            /** @var Expense $expense */
            /** @var LangTranslator $t */
            $t = $paramList->translator;
            $text = Expense::$typeList[$expense->getType()];
            return $t->get($text);
        });

        /** @noinspection PhpUnusedParameterInspection */
        $gridDataRender->addRender(Expense::COLUMN_PRICE, function ($expense, $paramList) {
            /** @var Expense $expense */
            return $expense->getPrice();
        });

        /** @noinspection PhpUnusedParameterInspection */
        $gridDataRender->addRender(Expense::COLUMN_CURRENCY, function ($expense, $paramList) {
            /** @var Expense $expense */
            return $expense->getCurrency();
        });

        /** @noinspection PhpUnusedParameterInspection */
        $gridDataRender->addRender(Expense::COLUMN_DESCRIPTION, function ($expense, $paramList) {
            /** @var Expense $expense */
            return $expense->getDescription();
        });

        /** @noinspection PhpUnusedParameterInspection */
        $gridDataRender->addRender(Expense::COLUMN_STATUS, function ($expense, $paramList) {
            /** @var Expense $expense */
            /** @var LangTranslator $t */
            $t = $paramList->translator;
            $text = Expense::$statusList[$expense->getStatus()];
            return $t->get($text);
        });
    }
}