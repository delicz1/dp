<?php
/**
 * Editace dopravnich prostredku
 */

namespace Proj\BussinesTrip\Component\Form;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use nil\Html;
use Notificator;
use Proj\Base\Entity\User;
use Proj\Base\Object\Form\DoctrineForm;
use Proj\Base\Object\Form\FormId;
use Proj\Base\Object\Locale\Formatter;
use Proj\BussinesTrip\Component\Dialog\EditTripUserDialog;
use Proj\BussinesTrip\Component\Grid\TripGrid;
use Proj\BussinesTrip\Component\Grid\TripUserGrid;
use Proj\BussinesTrip\Controller\TripController;
use Proj\BussinesTrip\Entity\Trip;
use Proj\BussinesTrip\Entity\TripUser;

/**
 * @author springer
 */
class EditTripUserForm extends DoctrineForm {

    //=====================================================
    //== Konstanty ========================================
    //=====================================================

    const ID     = FormId::EDIT_TRIP_USER;
    const ACTION = TripController::EDIT_TRIP_USER_FORM;
    const NAME   = 'EditTripUserForm';
    const INPUT_USER = 'inputUser';
    const INPUT_STATUS = 'inputStatus';
    const INPUT_TRIP = 'inputTrip';


    const SUBMIT = 'save';

    //=====================================================
    //== Konfigurace ======================================
    //=====================================================

    /**
     * @var TripUser
     */
    public $tripUser;

    /**
     * @param Formatter $formatter
     * @param \Request  $request
     * @param Registry  $doctrine
     * @param TripUser  $tripUser
     * @return EditUserForm
     */
    public static function create(Formatter $formatter, \Request $request = null, Registry $doctrine = null, TripUser $tripUser = null) {
        $form = new self(self::NAME, self::ACTION, self::POST);
        $form->setFormater($formatter);
        $form->addSubmit(self::SUBMIT, 'form.save', 'glyphicon glyphicon-floppy-disk');
        if ($request instanceof \Request) {
            $form->setRequest($request);
            $form->doctrine = $doctrine;
            $form->tripUser = $tripUser;
            $form->setHelpManager(false);
            $form->init();
        }
        return $form;
    }

    protected function init() {

        $userId = $this->tripUser->getUser() ? $this->tripUser->getUser()->getId() : '';

        $this->addHtml('trip');
        $this->addHidden(TripUser::COLUMN_ID, $this->tripUser->getId());
        $this->addHidden(self::INPUT_TRIP, $this->tripUser->getTrip()->getId());
        $this->addSelect(self::INPUT_USER, 'user.user', $userId, $this->getUserOptions())->addRuleRequired('');
        $this->addSelect(self::INPUT_STATUS, 'trip.status', $this->tripUser->getStatus(), TripUser::$statusList);
        $this->handle();
    }

    public function onSuccess() {
        $userId = $this[self::INPUT_USER]->getValue();
        $user = $this->doctrine->getRepository('ProjBaseBundle:User')->find($userId);

        $tripId = $this[self::INPUT_TRIP]->getValue();
        $trip = $this->doctrine->getRepository('ProjBussinesTripBundle:Trip')->find($tripId);


        $em = $this->getDoctrine()->getManager();
        $tripUser = $this->tripUser;

        $tripUser->setTrip($trip);
        $tripUser->setUser($user);
        $tripUser->setStatus($this[self::INPUT_STATUS]->getValue());

        $em->persist($tripUser);
        $em->flush();

        Notificator::add('SUCCESS', '', Notificator::TYPE_INFO);
        $js = EditTripUserDialog::close(EditTripUserDialog::DIV);
        $js .= TripGrid::reload(TripGrid::ID);
        $js .= TripUserGrid::reload(TripUserGrid::ID);
        echo Html::el('script')->setHtml($js);
    }

    public function onError() {
        Notificator::add('ERROR', '', Notificator::TYPE_ERROR);
    }



    //=====================================================
    //== Options ==========================================
    //=====================================================

    /**
     * @return array
     */
    private function getUserOptions() {
        $actualTripUserId = $this->tripUser->getId() ? $this->tripUser->getUser()->getId() : 0;
        $list = [];
        /** @var EntityRepository $repository */
        $repository = $this->doctrine->getRepository('ProjBaseBundle:User');
        /** @var QueryBuilder $qb */
        $qb = $repository->createQueryBuilder('u');
        $qb->where('u.status = ' . User::STATUS_ACTIVE);
        if ($this->tripUser->getId()) {
            $qb->orWhere('u.id = ' . $this->tripUser->getUser()->getId());
        }
        /** @var User[] $result */
        $result = $qb->getQuery()->getResult();
        foreach ($result as $user) {
            $list[$user->getId()] = $user->getFullName();
        }
        foreach ($this->tripUser->getTrip()->getTripUsers() as $tripUser) {
            $id = $tripUser->getUser()->getId();
            if ($tripUser->getUser()->getId() != $actualTripUserId) {
                unset($list[$id]);
            }
        }
        return $list;
    }

    //=====================================================
    //== Validace =========================================
    //=====================================================
}