<?php
/**
 * Created by PhpStorm.
 * User: springer
 * Date: 31.1.15
 * Time: 1:16
 */

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class jednotky prirazene ke sluzebni ceste
 * @package AppBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="trip_vehicle", indexes={@ORM\Index(name="trip_vehicle", columns={"trip_id", "vehicle_id"})})
 */
class TripVehicle {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="integer", name="trip_id")
     * @var int
     */
    protected $tripId;

    /**
     * @ORM\Column(type="integer", name="vehicle_id")
     * @var int
     */
    protected $vehicleId;

    /**
     * @ORM\ManyToOne(targetEntity="vehicle")
     * @ORM\JoinColumn(name="vehicle_id", referencedColumnName="id")
     * @var Vehicle
     */
    protected $vehicle = null;

    /**
     * @ORM\ManyToOne(targetEntity="trip")
     * @ORM\JoinColumn(name="trip_id", referencedColumnName="id")
     * @var Trip
     */
    protected $trip = null;

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getTripId() {
        return $this->tripId;
    }

    /**
     * @return int
     */
    public function getVehicleId() {
        return $this->vehicleId;
    }

    /**
     * @param int $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @param int $tripId
     */
    public function setTripId($tripId) {
        $this->tripId = $tripId;
    }

    /**
     * @param int $vehicleId
     */
    public function setVehicleId($vehicleId) {
        $this->vehicleId = $vehicleId;
    }
}