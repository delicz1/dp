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
use Proj\BussinesTrip\Component\Dialog\EditUserDialog;
use Proj\BussinesTrip\Controller\UserController;

/**
 * Class UserGrid
 * @package Proj\BussinesTrip\Component\Grid
 */
class UserGrid extends GridAjaxDoctrine {

    //=====================================================
    //== Konstanty ========================================
    //=====================================================

    const COLUMN_OPTIONS        = 'options';
    const COLUMN_ID             = 'id';
    const COLUMN_PASSWD         = 'passwd';
    const COLUMN_NAME           = 'name';
    const COLUMN_SURNAME        = 'surname';
    const COLUMN_ROLE           = 'role';
    const COLUMN_EMAIL          = 'email';

    const ID        = 'user_grid';
    const PAGER_ID  = 'user_grid_pager';

    const URL = UserController::USER_GRID_DATA;

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
//        $col->option->searchoptions->sopt = [self::SOPT_EQUAL];
        $col->option->index = 'u.id';
        $col->option->fixed = true;
        $col->option->width = 80;

        $this->addColumnGrid($col);


        $col = \GridColumn::create(self::COLUMN_EMAIL, 'email');
        $col->option->index = 'u.' . User::COLUMN_EMAIL;
        $col->option->search = true;
        $this->addColumnGrid($col);

        $col = \GridColumn::create(self::COLUMN_NAME, $t->get('user.name'));
        $col->option->index = 'u.' . User::COLUMN_NAME;
        $col->option->search = true;
        $this->addColumnGrid($col);

        $col = \GridColumn::create(self::COLUMN_SURNAME, $t->get('user.surname'));
        $col->option->index = 'u.' . User::COLUMN_SURNAME;
        $col->option->search = true;
        $this->addColumnGrid($col);

        $col = \GridColumn::create(User::COLUMN_STATUS, $t->get('user.status'));
        $col->option->index = 'u.' . User::COLUMN_STATUS;
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
        return $doctrine->getRepository('ProjBaseBundle:User');
    }

    /**
     * @param \Doctrine\ORM\EntityRepository $repository
     * @param \Doctrine\Bundle\DoctrineBundle\Registry $doctrine
     * @param $paramList
     * @param $gridFilter
     * @return \Doctrine\ORM\QueryBuilder|null
     */
    public static function getQueryBuilder(EntityRepository $repository, Registry $doctrine, $paramList, $gridFilter) {
        /** @var User $selfUser */
        $selfUser = $paramList->selfUser;
        /** @var \Doctrine\ORM\QueryBuilder $qb */
        $qb = $repository
            ->createQueryBuilder('u');
        if ($selfUser->getRole() == User::ROLE_USER) {
            $qb->where('u.id=' . $selfUser->getId());
        }
//            ->join('u.' . User::COLUMN_ROLES, 'r')
//            ->where('g.id = ' . $group->getId())
//            ->andWhere('BIT_AND(u.' . User::COLUMN_FLAG . ', :flag) = 0')
//            ->orderBy('u.id');
//            ->setParameters(['flag' => User::FLAG_DELETED]);
//        if (!$selfUser->can(Permission::ADMIN_ROLES)) {
//            $qb->andWhere(\Criteria::in('r.id', $selfUser->canIds(Permission::ADMIN_ROLES, new Role())));
//        }
        return $qb;
    }

    /**
     * @param \GridDataRender $gridDataRender
     * @param mixed           $paramList
     */
    public static function setRenders(\GridDataRender $gridDataRender, $paramList) {

        /** @noinspection PhpUnusedParameterInspection */
        $gridDataRender->addRender(self::COLUMN_OPTIONS, function ($user, $paramList) {
            /** @var User $user */
            /** @var LangTranslator $t */
            $t = $paramList->translator;
            $dialog = EditUserDialog::create($paramList->formatter, $user->getId());
            return self::getEditButton($t, $dialog->render(false, false));
        });

        /** @noinspection PhpUnusedParameterInspection */
        $gridDataRender->addRender(self::COLUMN_ID, function ($user, $paramList) {
            /** @var User $user */
            return $user->getId();
        });

        /** @noinspection PhpUnusedParameterInspection */
        $gridDataRender->addRender(self::COLUMN_EMAIL, function ($user, $paramList) {
            /** @var User $user */
            return $user->getEmail();
        });

        /** @noinspection PhpUnusedParameterInspection */
        $gridDataRender->addRender(self::COLUMN_NAME, function ($user, $paramList) {
            /** @var User $user */
            return $user->getName();
        });

        /** @noinspection PhpUnusedParameterInspection */
        $gridDataRender->addRender(self::COLUMN_SURNAME, function ($user, $paramList) {
            /** @var User $user */
            return $user->getSurname();
        });

        /** @noinspection PhpUnusedParameterInspection */
        $gridDataRender->addRender(User::COLUMN_STATUS, function ($user, $paramList) {
            /** @var User $user */
            /** @var LangTranslator $t */
            $t = $paramList->translator;
            $text = User::$statusList[$user->getStatus()];
            return $t->get($text);
        });

//        $gridDataRender->addRender(self::COLUMN_ROLE, function ($user, $paramList) {
//            /** @var User $user */
//            /** @var Formatter $formater */
//            $formater = $paramList->formater;
//            $tr = $formater->getLangTranslator();
//            return $tr->get($user->getMainRole()->getTitle());
//        });
    }
}