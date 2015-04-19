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

    const COLUMN_ID       = 'id';
    const COLUMN_NAME     = 'name';
    const COLUMN_TYPE     = 'type';
    const COLUMN_CAPACITY = 'capacity';
    const COLUMN_STATUS   = 'status';

    const TYPE_PERSONAL_CAR  = 1;
    const TYPE_BUSSINES_CAR  = 2;
    const TYPE_BUS           = 3;
    const TYPE_TRAIN         = 4;
    const TYPE_PLANE         = 5;
    const TYPE_PUBLIC_TRAFIC = 6;
    const TYPE_OTHER         = 7;

    const TYPE_PERSONAL_CAR_TRANS  = 'vehicle.type.1';
    const TYPE_BUSSINES_CAR_TRANS  = 'vehicle.type.2';
    const TYPE_BUS_TRANS           = 'vehicle.type.3';
    const TYPE_TRAIN_TRANS         = 'vehicle.type.4';
    const TYPE_PLANE_TRANS         = 'vehicle.type.5';
    const TYPE_PUBLIC_TRAFIC_TRANS = 'vehicle.type.6';
    const TYPE_OTHER_TRANS         = 'vehicle.type.7';

    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    const STATUS_ACTIVE_TRANS = 'vehicle.status.1';
    const STATUS_DELETED_TRANS = 'vehicle.status.2';

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
     * @var int
     * @ORM\Column(name="status", type="integer")
     */
    private $status;


    /**
     * @var ArrayCollection|Trip[]
     * @ORM\OneToMany(targetEntity="Trip", mappedBy="vehicle", cascade={"persist"})
     */
    protected $trips;

    public static $typeList = [
        self::TYPE_PERSONAL_CAR  => self::TYPE_PERSONAL_CAR_TRANS,
        self::TYPE_BUSSINES_CAR  => self::TYPE_BUSSINES_CAR_TRANS,
        self::TYPE_BUS           => self::TYPE_BUS_TRANS,
        self::TYPE_TRAIN         => self::TYPE_TRAIN_TRANS,
        self::TYPE_PLANE         => self::TYPE_PLANE_TRANS,
        self::TYPE_PUBLIC_TRAFIC => self::TYPE_PUBLIC_TRAFIC_TRANS,
        self::TYPE_OTHER         => self::TYPE_OTHER_TRANS,
    ];

    public $statusList = [
        self::STATUS_ACTIVE => self::STATUS_ACTIVE_TRANS,
        self::STATUS_DELETED => self::STATUS_DELETED_TRANS
    ];

    //=====================================================
    //== Konstruktor ======================================
    //=====================================================

    /**
     * Konstruktor
     */
    public function __construct() {
        $this->trips = new ArrayCollection();
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
     * @return string
     */
    public function getName() {
        return $this->name;
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