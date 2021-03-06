<?php
/**
 * @author springer
 */

namespace Proj\BussinesTrip\Report;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Proj\Base\Entity\User;
use Proj\Base\Object\Locale\Formatter;
use Proj\BussinesTrip\Component\Form\TravelOrderForm;
use Proj\BussinesTrip\Entity\Expense;
use Proj\BussinesTrip\Entity\Trip;
use Proj\BussinesTrip\Entity\TripUser;
use Proj\BussinesTrip\Entity\Vehicle;

/**
 * Class TravelOrderReport
 * @package Proj\BussinesTrip\Report
 */
class TravelOrderReport {

    const DATE = 'date';
    const FROM = 'from';
    const TO = 'to';
    const TIME_FROM = 'time_from';
    const TIME_TO = 'time_to';
    const VEHICLE = 'vehicle';
    const DISTANCE = 'distance';
    const E_FARE = 'e_fare';
    const E_DIET = 'e_diet';
    const E_OVERNIGHT = 'e_overnight';
    const E_OTHER = 'e_other';
    const TOTAL = 'e_total';
    const VEHICLE_TYPE = 'vehicle_type';


    private $fromPosition = '';
    private $meetinPlace = [];
    private $toPosition = '';
    private $purpose = '';


    /**
     * @var User
     */
    private $user;
    /**
     * @var \Request
     */
    private $request;
    /**
     * @var Registry
     */
    private $doctrine;

    /**
     * @var User
     */
    private $tripUser;

    public $page = 1;

    private $row = 0;
    private $data = [];
    private $pageTotal = [];

    /**
     * @var Formatter
     */
    private $formatter;


    /**
     * @param User      $user
     * @param \Request  $request
     * @param Registry  $doctrine
     * @param Formatter $formatter
     */
    public function __construct(User $user, \Request $request, Registry $doctrine, Formatter $formatter) {

        $this->user = $user;
        $this->request = $request;
        $this->doctrine = $doctrine;
        $this->formatter = $formatter;
        $this->tripUser = $doctrine->getRepository('ProjBaseBundle:User')->find($this->request->get(TravelOrderForm::SELECT_USERS));
        $this->status = $this->request->get(TravelOrderForm::SELECT_STATUS);

        $tripUserList = $this->getTripUserList();
        foreach ($tripUserList as $tripUser) {
            $this->row++;
            if ($this->row % 20 == 0) {
                $this->row = 1;
                $this->page++;
            }
            $trip = $tripUser->getTrip();
            if ($this->fromPosition == '') {
                $this->fromPosition = $trip->getPointFrom() . ', ' . $this->formatter->timestamp($trip->getTimeFrom(), Formatter::FORMAT_DATE_TIME);
                $this->purpose = $trip->getPurpose();
            }
            $this->addExpenses($tripUser);
            $this->setData(self::FROM, $trip->getPointFrom());
            $this->setData(self::TO, $trip->getPointTo());
            $this->setData(self::DATE, $this->formatter->timestamp($trip->getTimeFrom(), Formatter::FORMAT_DATE));
            $this->setData(self::TIME_FROM, $this->formatter->timestamp($trip->getTimeFrom(), Formatter::FORMAT_TIME));
            $this->setData(self::TIME_TO, $this->formatter->timestamp($trip->getTimeTo(), Formatter::FORMAT_TIME));
            $this->setData(self::VEHICLE, $this->getVehicleType($trip->getVehicle()));
            $this->setData(self::DISTANCE, $trip->getDistance());
            $this->meetinPlace[] = $trip->getPointTo();
            $this->toPosition = $trip->getPointFrom() . ', ' . $this->formatter->timestamp($trip->getTimeTo(), Formatter::FORMAT_DATE_TIME);
        }
    }

    /**
     * @param Vehicle $vehicle
     * @return string
     */
    private function getVehicleType(Vehicle $vehicle) {
        $type = '';
        switch($vehicle->getType()) {
            case Vehicle::TYPE_BUS:
                $type = 'A'; break;
            case Vehicle::TYPE_PERSONAL_CAR:
                $type = 'AUV'; break;
            case Vehicle::TYPE_TRAIN:
                $type = 'R'; break;
            case Vehicle::TYPE_BUSSINES_CAR:
                $type = 'AUT';
        }
        return $type;
    }

    /**
     * @param $type
     * @param $value
     */
    private function setData($type, $value) {
        $this->data[$this->page][$this->row][$type] = $value;
    }

    /**
     * @param $type
     * @param $value
     */
    private function addData($type, $value) {
        if (!isset($this->data[$this->page][$this->row][$type])) {
            $this->data[$this->page][$this->row][$type] = 0;
        }
        $this->data[$this->page][$this->row][$type] += $value;
    }

    /**
     * @param $type
     * @param $value
     */
    private function addPageData($type, $value) {
        if (!isset($this->pageTotal[$this->page][$type])) {
            $this->pageTotal[$this->page][$type] = 0;
        }
        $this->pageTotal[$this->page][$type] += $value;
    }

    /**
     * @param int    $page
     * @param int    $row
     * @param string $type
     * @param string $default
     * @return string
     */
    public function getData($page, $row, $type, $default = '') {
        $value = $default;
        if (isset($this->data[$page][$row][$type])) {
            $value = $this->data[$page][$row][$type];
        }
        return $value;
    }

    /**
     * @param int    $page
     * @param string $type
     * @return string
     */
    public function getPageData($page, $type) {
        $value = '';
        if (isset($this->pageTotal[$page][$type])) {
            $value = $this->pageTotal[$page][$type];
        }
        return $value;
    }
    /**
     * @return TripUser[]
     */
    private function getTripUserList() {
        $userId = $this->request->get(TravelOrderForm::SELECT_USERS);
        $timeFrom = $this->request->get(TravelOrderForm::INPUT_TIME_FROM);
        $timeTo = $this->request->get(TravelOrderForm::INPUT_TIME_TO);
        /** @var EntityRepository $repository */
        $repository = $this->doctrine->getRepository('ProjBussinesTripBundle:TripUser');
        /** @var QueryBuilder $qb */
        $qb = $repository->createQueryBuilder('tu');
        $qb->join('tu.trip', 't');
        $qb->join('tu.user', 'u');
        $qb->where('u.id =' . $userId);
        $qb->andWhere(' t.' . Trip::COLUMN_TIME_FROM . ' >= ' . (int)$timeFrom);
        $qb->andWhere('t.' . Trip::COLUMN_TIME_FROM . ' <= ' . (int)$timeTo);
        $qb->andWhere('tu.' . TripUser::COLUMN_STATUS . ' <= ' . (int)$this->status);
        $qb->orderBy('t.' . Trip::COLUMN_TIME_FROM);
        return $qb->getQuery()->getResult();
    }

    /**
     * @param TripUser $tripUser
     */
    private function addExpenses(TripUser $tripUser) {
        $expenseList = $tripUser->getExpenses();
        foreach ($expenseList as $expense) {
            $type = self::E_OTHER;
            if ($expense->getStatus() != $this->status) {
                continue;
            }
            switch($expense->getType()) {
                case Expense::TYPE_DIET:
                    $type = self::E_DIET; break;
                case Expense::TYPE_FARE:
                    $type = self::E_FARE; break;
                case Expense::TYPE_OVERNIGHT_STAY:
                    $type = self::E_OVERNIGHT; break;
            }
            $this->addData($type, $expense->getPrice());
            $this->addData(self::TOTAL, $expense->getPrice());
            $this->addPageData($type, $expense->getPrice());
            $this->addPageData(self::TOTAL, $expense->getPrice());
        }
    }


    /**
     * @return string
     */
    public function getFromPosition() {
        return $this->fromPosition;
    }

    /**
     * @return string
     */
    public function getPurpose() {
        return $this->purpose;
    }

    /**
     * @return string
     */
    public function getToPosition() {
        return $this->toPosition;
    }

    /**
     * @return User
     */
    public function getTripUser() {
        return $this->tripUser;
    }

    /**
     * @return int
     */
    public function getPage() {
        return $this->page;
    }

    /**
     * @return string
     */
    public function getMeetingPlaceString() {
        return implode(', ', $this->meetinPlace);
    }
}