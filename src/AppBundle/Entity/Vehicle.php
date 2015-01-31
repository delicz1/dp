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
     * @ORM\Column(name="number_plate")
     * @var string
     */
    protected $numberPlate;

    /**
     * @ORM\Column(name="capacity")
     * @var int
     */
    protected $capacity;

    /**
     * @ORM\ManyToMany(targetEntity="trip")
     * @ORM\JoinTable(name="trip_vehicle",
     *      joinColumns={@ORM\JoinColumn(name="vehicle_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="trip_id", referencedColumnName="id")})
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
}