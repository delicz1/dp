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
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * Class Vehicle
 * @package AppBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="vehicle")
 */
class Vehicle {

    const TYPE_CAR = 1;
    const TYPE_TRAIN = 2;
    const TYPE_BUS = 3;
    const TYPE_PLANE = 4;
    const TYPE_OTHER = 10;

    const TYPE_CAR_TR = 'Osobní auto';
    const TYPE_TRAIN_TR = 'Vlak';
    const TYPE_BUS_TR = 'Autobus';
    const TYPE_PLANE_TR = 'Letadlo';
    const TYPE_OTHER_TR = 'Jiné';

    public static $typeList = [
        self::TYPE_CAR => self::TYPE_CAR_TR,
        self::TYPE_TRAIN => self::TYPE_TRAIN_TR,
        self::TYPE_BUS => self::TYPE_BUS_TR,
        self::TYPE_PLANE => self::TYPE_PLANE_TR,
        self::TYPE_OTHER => self::TYPE_OTHER_TR,
    ];

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=10, name="number_plate")
     * @var string
     */
    protected $numberPlate;

    /**
     * @ORM\Column(type="integer", name="capacity")
     * @var int
     */
    protected $capacity;

    /**
     * Typ dopravniho prostredku. Auto, Autobus, Letadlo, jiné
     * @ORM\Column(type="integer")
     * @var
     */
    protected $type;


    /**
     * @ORM\ManyToMany(targetEntity="Trip", inversedBy="vehicles")
     * @ORM\JoinTable(name="trips_vehicles")
     * @var ArrayCollection
     */
    protected $trips;

    //== Staticke metody

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('name', new NotBlank());
        $metadata->addPropertyConstraint('numberPlate', new NotBlank());
    }

    public function __construct() {
        $this->trips = new ArrayCollection();
    }

    //== Getry

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }


    /**
     * @return string
     */
    public function getNumberPlate() {
        return $this->numberPlate;
    }

    /**
     * @return int
     */
    public function getCapacity() {
        return $this->capacity;
    }

    /**
     * @return integer
     */
    public function getType() {
        return $this->type;
    }


    //== Setry

    /**
     * @param int $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @param string $numberPlate
     */
    public function setNumberPlate($numberPlate) {
        $this->numberPlate = $numberPlate;
    }

    /**
     * @param int $capacity
     */
    public function setCapacity($capacity) {
        $this->capacity = $capacity;
    }

    /**
     * @param int $type
     */
    public function setType($type) {
        $this->type = $type;
    }



    /**
     * Add trips
     *
     * @param Trip $trips
     * @return Vehicle
     */
    public function addTrip(Trip $trips)
    {
        $this->trips[] = $trips;

        return $this;
    }

    /**
     * Remove trips
     *
     * @param Trip $trips
     */
    public function removeTrip(Trip $trips)
    {
        $this->trips->removeElement($trips);
    }

    /**
     * Get trips
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTrips()
    {
        return $this->trips;
    }
}
