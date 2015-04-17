<?php
/**
 * @author springer
 */

namespace Proj\BussinesTrip\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Trip
 * @package Proj\BussinesTrip\Entity
 * @ORM\Entity
 * @ORM\Table(name="trip_point", indexes={@ORM\Index(name="trip", columns={"trip_id"})})
 */
class TripPoint {

    //=====================================================
    //== Konstanty ========================================
    //=====================================================

    const COLUMN_ID = 'id';
    const COLUMN_TIME_FROM = 'timeFrom';
    const COLUMN_TIME_TO = 'timeTo';
    const COLUMN_POINT = 'point';

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
     * @ORM\Column(name="point", type="string", length=100)
     */
    private $point;

    /**
     * @var Trip
     * @ORM\ManyToOne(targetEntity="Trip", inversedBy="id")
     * @ORM\JoinColumns(
     *   @ORM\JoinColumn(name="trip_id", referencedColumnName="id")
     * )
     */
    protected $trip;

    //=====================================================
    //== Konstruktor ======================================
    //=====================================================

    /**
     * Konstruktor
     */
    public function __construct() {}

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
    public function getPoint() {
        return $this->point;
    }

    /**
     * @param string $point
     */
    public function setPointFrom($point) {
        $this->point = $point;
    }

    /**
     * @return Trip
     */
    public function getTrip() {
        return $this->trip;
    }

    /**
     * @param Trip $trip
     */
    public function setTrip($trip) {
        $this->trip = $trip;
    }


}