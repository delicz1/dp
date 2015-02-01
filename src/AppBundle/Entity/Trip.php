<?php
/**
 * Created by PhpStorm.
 * User: springer
 * Date: 21.1.15
 * Time: 20:13
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Sluzebni cesta
 * @package AppBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="trip", indexes={@ORM\Index(name="time_from", columns={"time_from"})})
 */
class Trip {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="datetimetz", name="time_from")
     * @var int
     */
    protected $timeFrom;

    /**
     * @ORM\Column(type="datetimetz", name="time_to")
     * @var int
     */
    protected $timeTo;

    /**
     * @ORM\Column(type="integer", name="capacity")
     * @var int
     */
    protected $distance;

    /**
     * @ORM\Column(type="string", name="from_point", length=50)
     * @var string
     */
    protected $fromPoint;

    /**
     * @ORM\Column(type="string", name="to_point", length=50)
     * @var string
     */
    protected $toPoint;

    /**
     * @ORM\Column(type="string", name="purpose", length=150)
     * @var string
     */
    protected $purpose;

    /**
     * @ORM\ManyToMany(targetEntity="Vehicle", mappedBy="trips")
     * @var ArrayCollection
     */
    protected $vehicles;

    public function __construct() {
        $this->vehicles = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getTimeFrom() {
        return $this->timeFrom;
    }

    /**
     * @return int
     */
    public function getTimeTo() {
        return $this->timeTo;
    }

    /**
     * @return int
     */
    public function getDistance() {
        return $this->distance;
    }

    /**
     * @return string
     */
    public function getFromPoint() {
        return $this->fromPoint;
    }

    /**
     * @return string
     */
    public function getToPoint() {
        return $this->toPoint;
    }

    /**
     * @return string
     */
    public function getPurpose() {
        return $this->purpose;
    }

    /**
     * @param int $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @param int $timeFrom
     */
    public function setTimeFrom($timeFrom) {
        $this->timeFrom = $timeFrom;
    }

    /**
     * @param int $timeTo
     */
    public function setTimeTo($timeTo) {
        $this->timeTo = $timeTo;
    }

    /**
     * @param int $distance
     */
    public function setDistance($distance) {
        $this->distance = $distance;
    }

    /**
     * @param string $fromPoint
     */
    public function setFromPoint($fromPoint) {
        $this->fromPoint = $fromPoint;
    }

    /**
     * @param string $toPoint
     */
    public function setToPoint($toPoint) {
        $this->toPoint = $toPoint;
    }

    /**
     * @param string $purpose
     */
    public function setPurpose($purpose) {
        $this->purpose = $purpose;
    }

    /**
     * Add vehicles
     *
     * @param Vehicle $vehicles
     * @return Trip
     */
    public function addVehicle(Vehicle $vehicles)
    {
        $this->vehicles[] = $vehicles;

        return $this;
    }

    /**
     * Remove vehicles
     *
     * @param Vehicle $vehicles
     */
    public function removeVehicle(Vehicle $vehicles)
    {
        $this->vehicles->removeElement($vehicles);
    }

    /**
     * Get vehicles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVehicles()
    {
        return $this->vehicles;
    }
}
