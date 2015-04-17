<?php
/**
 * @author springer
 */

namespace Proj\BussinesTrip\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Vehicle
 * @package Proj\BussinesTrip\Entity
 * @ORM\Entity
 * @ORM\Table(name="vehicle")
 */
class Vehicle {

    //=====================================================
    //== Konstanty ========================================
    //=====================================================

    const COLUMN_ID = 'id';
    const COLUMN_NAME = 'name';
    const COLUMN_TYPE = 'type';
    const COLUMN_CAPACITY = 'capacity';

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
     * @var string
     * @ORM\Column(name="name", type="string", length=50)
     */
    private $name;

    /**
     * @var int
     * @ORM\Column(name="type", type="integer")
     */
    private $type;

    /**
     * @var int
     * @ORM\Column(name="capacity", type="integer")
     */
    private $capacity;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Trip", mappedBy="vehicle", cascade={"persist"})
     */
    protected $trips;

    //=====================================================
    //== Konstruktor ======================================
    //=====================================================

    /**
     * Konstruktor
     */
    public function __construct(){}

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
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param int $type
     */
    public function setType($type) {
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getCapacity() {
        return $this->capacity;
    }

    /**
     * @param int $capacity
     */
    public function setCapacity($capacity) {
        $this->capacity = $capacity;
    }
}