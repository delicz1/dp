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
}