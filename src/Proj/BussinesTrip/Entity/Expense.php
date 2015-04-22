<?php
/**
 * @author springer
 */

namespace Proj\BussinesTrip\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Vehicle
 * @package Proj\BussinesTrip\Entity
 * @ORM\Entity
 * @ORM\Table(name="expense", indexes={@ORM\Index(name="trip_user", columns={"trip_user_id"})})
 */
class Expense {

    //=====================================================
    //== Konstanty ========================================
    //=====================================================

    const COLUMN_ID = 'id';
    const COLUMN_PRICE = 'price';
    const COLUMN_TYPE = 'type';
    const COLUMN_CURRENCY = 'currency';
    const COLUMN_DESCRIPTION = 'description';
    const COLUMN_STATUS = 'status';

    const TYPE_OVERNIGHT_STAY = 1;
    const TYPE_FARE = 2;
    const TYPE_DIET = 3;
    const TYPE_OTHER_EXPENSE = 4;

    const TYPE_OVERNIGHT_STAY_TRANS = 'expense.type.1';
    const TYPE_FARE_TRANS = 'expense.type.2';
    const TYPE_DIET_TRANS = 'expense.type.3';
    const TYPE_OTHER_EXPENSE_TRANS = 'expense.type.4';


    const STATUS_NEW = 1;
    const STATUS_APPROVED = 2;
    const STATUS_REJECTED = 3;

    const STATUS_NEW_TRANS = 'expense.status.1';
    const STATUS_APPROVED_TRANS = 'expense.status.2';
    const STATUS_REJECTED_TRANS = 'expense.status.3';

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
     * @ORM\Column(name="type", type="integer")
     */
    private $type;

    /**
     * @var double
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var string
     * @ORM\Column(name="currency", type="string", length=30)
     */
    private $currency;

    /**
     * @var string
     * @ORM\Column(name="description", type="string", length=200)
     */
    private $description;

    /**
     * @var int
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @var TripUser
     * @ORM\ManyToOne(targetEntity="TripUser", inversedBy="id")
     * @ORM\JoinColumns(
     *   @ORM\JoinColumn(name="trip_user_id", referencedColumnName="id")
     * )
     */
    protected  $tripUser;

    public static $statusList = [
        self::STATUS_NEW      => self::STATUS_NEW_TRANS,
        self::STATUS_APPROVED => self::STATUS_APPROVED_TRANS,
        self::STATUS_REJECTED => self::STATUS_REJECTED_TRANS
    ];

    public static $typeList = [
        self::TYPE_OVERNIGHT_STAY => self::TYPE_OVERNIGHT_STAY_TRANS,
        self::TYPE_FARE => self::TYPE_FARE_TRANS,
        self::TYPE_DIET => self::TYPE_DIET_TRANS,
        self::TYPE_OTHER_EXPENSE => self::TYPE_OTHER_EXPENSE_TRANS,
    ];

    //=====================================================
    //== Konstruktor ======================================
    //=====================================================

    /**
     * Konstruktor
     */
    public function __construct(){
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
     * @return float
     */
    public function getPrice() {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice($price) {
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getCurrency() {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency($currency) {
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description) {
        $this->description = $description;
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
     * @return TripUser
     */
    public function getTripUser() {
        return $this->tripUser;
    }

    /**
     * @param TripUser $tripUser
     */
    public function setTripUser($tripUser) {
        $this->tripUser = $tripUser;
    }

    /**
     * @return array
     */
    public static function getTypeList() {
        return self::$typeList;
    }

    /**
     * @return array
     */
    public static function getStatusList() {
        return self::$statusList;
    }


}