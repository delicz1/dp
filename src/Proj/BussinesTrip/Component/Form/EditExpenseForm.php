<?php
/**
 * Editace nakladu
 */

namespace Proj\BussinesTrip\Component\Form;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityRepository;
use nil\Html;
use Notificator;
use Proj\Base\Entity\User;
use Proj\Base\Object\Form\DoctrineForm;
use Proj\Base\Object\Form\FormId;
use Proj\Base\Object\Locale\Formatter;
use Proj\BussinesTrip\Component\Dialog\EditExpenseDialog;
use Proj\BussinesTrip\Component\Dialog\EditVehicleDialog;
use Proj\BussinesTrip\Component\Grid\ExpenseGrid;
use Proj\BussinesTrip\Component\Grid\VehicleGrid;
use Proj\BussinesTrip\Controller\ExpenseController;
use Proj\BussinesTrip\Controller\VehicleController;
use Proj\BussinesTrip\Entity\Expense;
use Proj\BussinesTrip\Entity\Trip;
use Proj\BussinesTrip\Entity\TripUser;
use Proj\BussinesTrip\Entity\Vehicle;

/**
 * @author springer
 */
class EditExpenseForm extends DoctrineForm {

    //=====================================================
    //== Konstanty ========================================
    //=====================================================

    const ID     = FormId::EDIT_EXPENSE;
    const ACTION = ExpenseController::EDIT_FORM;
    const NAME   = 'EditExpenseForm';

    const PARAM_USER         = 'user_id';
    const PARAM_TRIP         = 'trip_id';
    const INPUT_TRIP_USER_ID = 'trip_user_id';
    const INPUT_TYPE         = 'type';
    const INPUT_PRICE        = 'price';
    const INPUT_CURRENCY     = 'currency';
    const INPUT_DESCRIPTION  = 'description';
    const INPUT_STATUS       = 'status';

    const SUBMIT = 'save';

    /**
     * @var User
     */
    public $selfUser;

    /** @var Expense     */
    private $expense;
    /** @var  Trip */
    private $trip;

    //=====================================================
    //== Konfigurace ======================================
    //=====================================================


    /**
     * @param Formatter $formatter
     * @param \Request  $request
     * @param Registry  $doctrine
     * @param Expense   $expense
     * @param Trip      $trip
     * @param User      $selfUser
     * @return EditUserForm
     */
    public static function create(Formatter $formatter, \Request $request = null, Registry $doctrine = null, Expense $expense = null, Trip $trip = null, User $selfUser = null) {
        $form = new self(self::NAME, self::ACTION, self::POST);
        $form->setFormater($formatter);
        $form->addSubmit(self::SUBMIT, 'form.save', 'glyphicon glyphicon-floppy-disk');
        if ($request instanceof \Request) {
            $form->setRequest($request);
            $form->doctrine = $doctrine;
            $form->expense = $expense;
            $form->trip = $trip;
            $form->selfUser = $selfUser;
            $form->setHelpManager(false);
            $form->init();
        }
        return $form;
    }

    protected function init() {

        $tr = $this->formater->getLangTranslator();
        $statusValue = $this->expense->getId() ? $this->expense->getStatus() : Expense::STATUS_NEW;
        $tripUser = $this->expense->getId() ? $this->expense->getTripUser()->getId() : '';
        $tripId = $this->request->get(self::PARAM_TRIP);

        $this->addHidden(Expense::COLUMN_ID, $this->expense->getId());
        $this->addHidden(self::PARAM_TRIP, $this->trip->getId());

        $this->addSelect(self::INPUT_TRIP_USER_ID, $tr->get('user.user'), $tripUser, $this->getTripUserOption($tripId))->addRuleRequired('');
        $this->addText(self::INPUT_PRICE, $tr->get('expense.price'), $this->expense->getPrice())->addRuleFloat();
        $this->addText(self::INPUT_CURRENCY, $tr->get('expense.currency'), $this->expense->getCurrency());
        $this->addText(self::INPUT_DESCRIPTION, $tr->get('expense.description'), $this->expense->getDescription());
        $this->addSelect(self::INPUT_TYPE, $tr->get('expense.type'), $this->expense->getType(), Expense::$typeList);
        if (!$this->selfUser->isRoleUser()) {
            $this->addSelect(self::INPUT_STATUS, $tr->get('expense.status'), $statusValue, Expense::$statusList);
        } else {
            $this->addHidden(self::INPUT_STATUS, $statusValue);
        }

        $this->handle();
    }

    public function onSuccess() {

        $em = $this->getDoctrine()->getManager();
        $expense = $this->expense;

        $tripUserId = $this[self::INPUT_TRIP_USER_ID]->getValue();
        $tripUser = $this->doctrine->getRepository('ProjBussinesTripBundle:TripUser')->find($tripUserId);

        if ($tripUser) {
            $expense->setTripUser($tripUser);
            $expense->setPrice($this[self::INPUT_PRICE]->getValue());
            $expense->setCurrency($this[self::INPUT_CURRENCY]->getValue());
            $expense->setDescription($this[self::INPUT_DESCRIPTION]->getValue());
            $expense->setType($this[self::INPUT_TYPE]->getValue());
            $expense->setStatus($this[self::INPUT_STATUS]->getValue());

            $em->persist($expense);
            $em->flush();

            Notificator::add('SUCCESS', '', Notificator::TYPE_INFO);
            $js = EditExpenseDialog::close(EditExpenseDialog::DIV);
            $js .= ExpenseGrid::reload(ExpenseGrid::ID);
            echo Html::el('script')->setHtml($js);
        }
    }

    public function onError() {
        Notificator::add('ERROR', '', Notificator::TYPE_ERROR);
    }



    //=====================================================
    //== Options ==========================================
    //=====================================================

    /**
     * @param $tripId
     * @return string[]
     */
    private function getTripUserOption($tripId) {
        $result = [];
        /** @var EntityRepository $repo */
        $repo = $this->doctrine->getRepository('ProjBussinesTripBundle:TripUser');
        $qb = $repo->createQueryBuilder('tu');
        $qb->join('tu.trip', 't');
        $qb->join('tu.user', 'u');
        $qb->where('t.id = ' . $tripId);
        if ($this->selfUser->isRoleUser()) {
            $qb->andWhere('u.id =' . $this->selfUser->getId());
        }
        /** @var TripUser[] $resultList */
        $resultList = $qb->getQuery()->getResult();
        foreach ($resultList as $tripUser) {
            $result[$tripUser->getId()] = $tripUser->getUser()->getFullName();
        }
        return $result;
    }

    //=====================================================
    //== Validace =========================================
    //=====================================================
}