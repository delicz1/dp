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
use Proj\Base\Object\Form\FormUtil;
use Proj\Base\Object\Locale\Formatter;
use Proj\BussinesTrip\Component\Dialog\EditTripDialog;
use Proj\BussinesTrip\Component\Grid\TripGrid;
use Proj\BussinesTrip\Controller\TripController;
use Proj\BussinesTrip\Entity\Trip;
use Proj\BussinesTrip\Entity\TripUser;
use Proj\BussinesTrip\Entity\Vehicle;

/**
 * @author springer
 */
class EditTripForm extends DoctrineForm {

    //=====================================================
    //== Konstanty ========================================
    //=====================================================

    const ID     = FormId::EDIT_TRIP;
    const ACTION = TripController::EDIT_FORM;
    const NAME   = 'EditTripForm';
    const SELECT_USERS = 'selectedUsers';
    const INPUT_USERS = 'inputUsers';

    const BTN_ADD = 'new_btn';
    const BTN_DELETE = 'delete_btn';

    const SUBMIT = 'save';

    //=====================================================
    //== Konfigurace ======================================
    //=====================================================

    /**
     * @var Trip
     */
    public $trip;

    /**
     * @param Formatter $formatter
     * @param \Request  $request
     * @param Registry  $doctrine
     * @param Trip      $trip
     * @return EditUserForm
     */
    public static function create(Formatter $formatter, \Request $request = null, Registry $doctrine = null, Trip $trip = null) {
        $form = new self(self::NAME, self::ACTION, self::POST);
        $form->setFormater($formatter);
        $form->addSubmit(self::SUBMIT, 'form.save', 'glyphicon glyphicon-floppy-disk');
        $form->addSubmit(self::BTN_ADD, 'form.add', 'icon-system-20x20 add');
        if ($request instanceof \Request) {
            $form->setRequest($request);
            $form->doctrine = $doctrine;
            $form->trip = $trip;
            $form->setHelpManager(false);
            $form->init();
        }
        return $form;
    }

    protected function init() {

        $status = Trip::STATUS_NEW;
        $timeFrom = \DateUtil::getStartDay();
        $timeTo = \DateUtil::getEndDay();
        $vehicleId = 0;
        if ($this->trip->getId()) {
            $timeFrom = $this->trip->getTimeFrom();
            $timeTo = $this->trip->getTimeTo();
            $status = $this->trip->getStatus();
            $vehicleId = $this->trip->getVehicle()->getId();
        }

        $this->addHidden('id', $this->trip->getId());
        $this->addDate(Trip::COLUMN_TIME_FROM, 'trip.time.from', $timeFrom, \FormItemDate::MODE_DATETIME);
        $this->addDate(Trip::COLUMN_TIME_TO, 'trip.time.to', $timeTo, \FormItemDate::MODE_DATETIME);
        $this->addText(Trip::COLUMN_POINT_FROM, 'trip.point.from', $this->trip->getPointFrom());
        $this->addText(Trip::COLUMN_POINT_TO, 'trip.point.to', $this->trip->getPointTo());
        $this->addText(Trip::COLUMN_DISTANCE, 'trip.distance', $this->trip->getDistance())->addRuleInteger('', true);
        $this->addText(Trip::COLUMN_PURPOSE, 'trip.purpose', $this->trip->getPurpose());
        $this->addSelect(Trip::COLUMN_VEHICLE_ID, 'vehicle.vehicle', $vehicleId, $this->getVehicleOptions());
        $this->addSelect(Trip::COLUMN_STATUS, 'trip.status', $status, Trip::$statusList);

//        /** @var TripUser[] $list */
//        $list = $this->trip->getTripUsers();
//        $userList = [];
//        foreach ($list as $tripUser) {
//            $userList[$tripUser->getUser()->getId()] = $tripUser->getUser();
//        }
//
//        /** @var User[] $userList */
//        $userList = FormUtil::handleMultiItem($this, new User(), $userList, self::BTN_ADD, self::BTN_DELETE , self::INPUT_USERS);
//        foreach($userList as $user) {
//            $id = $user->getId();
//            $parent = $this->addSelect(self::SELECT_USERS . '['.$id.']', 'Uzivatele', $user->getId(), $this->getUsersOptions());
////            if(!ContractUtil::isContractSettingUsed($goods)) {
//                $child = $this->addButton(self::BTN_DELETE.$id,'', 'icon icon-system-20x20 delete',
//                    $this->getHandler()->submit(self::BTN_DELETE, [self::INPUT_USERS => $user->getId()]))
//                    ->setAttr('style','margin-left: 6px; border-width: 1px');
//                $parent->addChildItem($child);
////            }
//        }


        $this->handle();
    }

    public function onSuccess() {
        $save = $this->getRequest()->getParam(self::SUBMIT);
        if ($save) {
            $vehicleId = $this[Trip::COLUMN_VEHICLE_ID]->getValue();
            $vehicle = $this->doctrine->getRepository('ProjBussinesTripBundle:Vehicle')->find($vehicleId);
            $em = $this->getDoctrine()->getManager();
            $trip = $this->trip;

            $trip->setTimeFrom($this[Trip::COLUMN_TIME_FROM]->getValue());
            $trip->setTimeTo($this[Trip::COLUMN_TIME_TO]->getValue());
            $trip->setPointFrom($this[Trip::COLUMN_POINT_FROM]->getValue());
            $trip->setPointTo($this[Trip::COLUMN_POINT_TO]->getValue());
            $trip->setDistance($this[Trip::COLUMN_DISTANCE]->getValue());
            $trip->setPurpose($this[Trip::COLUMN_PURPOSE]->getValue());
            $trip->setStatus($this[Trip::COLUMN_STATUS]->getValue());
            $trip->setVehicle($vehicle);

            $em->persist($trip);
            $em->flush();

            Notificator::add('SUCCESS', '', Notificator::TYPE_INFO);
            $js = EditTripDialog::close(EditTripDialog::DIV);
            $js .= TripGrid::reload(TripGrid::ID);
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
     * @return array
     */
    private function getVehicleOptions() {
        $list = [];
        /** @var EntityRepository $repository */
        $repository = $this->doctrine->getRepository('ProjBussinesTripBundle:Vehicle');
        /** @var QueryBuilder $qb */
        $qb = $repository->createQueryBuilder('v');
        $qb->where('v.status = ' . Vehicle::STATUS_ACTIVE);
        if ($this->trip->getId()) {
            $qb->orWhere('v.id = ' . $this->trip->getVehicle()->getId());
        }
        /** @var Vehicle[] $result */
        $result = $qb->getQuery()->getResult();
        foreach ($result as $vehicle) {
            $list[$vehicle->getId()] = $vehicle->getName();
        }
        return $list;
    }

//    /**
//     * @return array
//     */
//    private function getUsersOptions() {
//        $result = [];
//        $repository = $this->doctrine->getRepository('ProjBaseBundle:User');
//        /** @var User[] $list */
//        $list = $repository->findAll();
//        foreach ($list as $item) {
//            $result[$item->getId()] = $item->getFullName();
//        }
//        return $result;
//    }

    //=====================================================
    //== Validace =========================================
    //=====================================================
}