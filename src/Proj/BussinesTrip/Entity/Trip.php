<?php
/**
 * @author springer
 */

namespace Proj\BussinesTrip\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Proj\Base\Object\Locale\Formatter;

/**
 * Class Trip
 * @package Proj\BussinesTrip\Entity
 * @ORM\Entity
 * @ORM\Table(name="trip", indexes={@ORM\Index(name="vehicle", columns={"vehicle_id"})})
 */
class Trip {

    //=====================================================
    //== Konstanty ========================================
    //=====================================================

    const COLUMN_ID = 'id';
    const COLUMN_VEHICLE_ID = 'vehicleId';
    const COLUMN_TIME_FROM = 'timeFrom';
    const COLUMN_TIME_TO = 'timeTo';
    const COLUMN_POINT_FROM = 'pointFrom';
    const COLUMN_POINT_TO = 'pointTo';
    const COLUMN_PURPOSE = 'purpose';
    const COLUMN_DISTANCE = 'distance';
    const COLUMN_STATUS = 'status';

    const STATUS_NEW = 1;
    const STATUS_APPROVED = 2;
    const STATUS_REJECTED = 3;

    const STATUS_NEW_TRANS = 'trip.status.1';
    const STATUS_APPROVED_TRANS = 'trip.status.2';
    const STATUS_REJECTED_TRANS = 'trip.status.3';

    //=====================================================
    //== ORM ==============================================
    //=====================================================

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;


    /**
     * @var int
     * @ORM\Column(name="time_from", type="integer");
     */
    private $timeFrom;

    /**
     * @var int
     * @ORM\Column(name="time_to", type="integer")
     */
    private $timeTo;

    /**
     * @var string
     * @ORM\Column(name="point_from", type="string", length=100)
     */
    private $pointFrom;

    /**
     * @var string
     * @ORM\Column(name="point_to", type="string", length=100)
     */

    private $pointTo;
    /**
     * @var string
     * @ORM\Column(name="purpose", type="string", length=150)
     */
    private $purpose;

    /**
     * @var float
     * @ORM\Column(name="distance", type="float", nullable=true)
     */
    private $distance;

    /**
     * @var int
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @var Vehicle
     * @ORM\ManyToOne(targetEntity="Vehicle", inversedBy="id")
     * @ORM\JoinColumns(
     *   @ORM\JoinColumn(name="vehicle_id", referencedColumnName="id")
     * )
     */
    protected $vehicle;

    /**
     * @var ArrayCollection|Trip[]
     * @ORM\OneToMany(targetEntity="TripPoint", mappedBy="trip", cascade={"persist"})
     */
    protected $tripPoints;

    /**
     * @var ArrayCollection|TripUser[]
     * @ORM\OneToMany(targetEntity="TripUser", mappedBy="trip", cascade={"persist"})
     */
    public $tripUsers;

    public static $statusList = [
        self::STATUS_NEW      => self::STATUS_NEW_TRANS,
        self::STATUS_APPROVED => self::STATUS_APPROVED_TRANS,
        self::STATUS_REJECTED => self::STATUS_REJECTED_TRANS
    ];


    //=====================================================
    //== Konstruktor ======================================
    //=====================================================

    /**
     * Konstruktor
     */
    public function __construct() {
        $this->tripPoints = new ArrayCollection();
        $this->tripUsers = new ArrayCollection();
    }

    //=====================================================
    //== Set/Get ==========================================
    //=====================================================

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getTimeFrom() {
        return $this->timeFrom;
    }

    /**
     * @param int $timeFrom
     */
    public function setTimeFrom($timeFrom) {
        $this->timeFrom = $timeFrom;
    }

    /**
     * @return int
     */
    public function getTimeTo() {
        return $this->timeTo;
    }

    /**
     * @param int $timeTo
     */
    public function setTimeTo($timeTo) {
        $this->timeTo = $timeTo;
    }

    /**
     * @return string
     */
    public function getPointFrom() {
        return $this->pointFrom;
    }

    /**
     * @param string $pointFrom
     */
    public function setPointFrom($pointFrom) {
        $this->pointFrom = $pointFrom;
    }

    /**
     * @return string
     */
    public function getPointTo() {
        return $this->pointTo;
    }

    /**
     * @param string $pointTo
     */
    public function setPointTo($pointTo) {
        $this->pointTo = $pointTo;
    }

    /**
     * @return string
     */
    public function getPurpose() {
        return $this->purpose;
    }

    /**
     * @param string $purpose
     */
    public function setPurpose($purpose) {
        $this->purpose = $purpose;
    }

    /**
     * @return float
     */
    public function getDistance() {
        return $this->distance;
    }

    /**
     * @param float $distance
     */
    public function setDistance($distance) {
        $this->distance = $distance;
    }

    /**
     * @return int
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus($status) {
        $this->status = $status;
    }

    /**
     * @return Vehicle
     */
    public function getVehicle() {
        return $this->vehicle;
    }

    /**
     * @param Vehicle $vehicle
     */
    public function setVehicle($vehicle) {
        $this->vehicle = $vehicle;
    }

    /**
     * @return ArrayCollection|Trip[]
     */
    public function getTripPoints() {
        return $this->tripPoints;
    }

    /**
     * @param ArrayCollection|Trip[] $tripPoints
     */
    public function setTripPoints($tripPoints) {
        $this->tripPoints = $tripPoints;
    }

    /**
     * @return ArrayCollection|TripUser[]
     */
    public function getTripUsers() {
        return $this->tripUsers;
    }

    /**
     * @param ArrayCollection|TripUser[] $tripUsers
     */
    public function setTripUsers($tripUsers) {
        $this->tripUsers = $tripUsers;
    }

    /**
     * @param Formatter $formatter
     * @return string
     */
    public function toString(Formatter $formatter) {

        $vehicle = $this->getVehicle();

        $text = $vehicle ? $vehicle->getName() . ' ' : '';
        $text .= $formatter->timestamp($this->getTimeFrom(), Formatter::FORMAT_DATE_TIME) . ' - ';
        $text .= $formatter->timestamp($this->getTimeTo(), Formatter::FORMAT_DATE_TIME);
        $text .= $this->getPurpose() ? ', ' . $this->getPurpose(): '';
        return $text;
    }

    public function getFreeCapacity() {
        $capacity = '';
        $vehicle = $this->getVehicle();
        if (in_array($vehicle->getType(), Vehicle::$carList)) {
            $tripUserList = $this->getTripUsers();
            $capacity = $vehicle->getCapacity();
            foreach ($tripUserList as $tripUser) {
                if ($tripUser->getStatus() != TripUser::STATUS_REJECTED) {
                    $capacity--;
                }
            }
        }
        return $capacity;
    }
}