<?php
/**
 * @author springer
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Cost
 * @package AppBundle\Entity
 * @ORM\Entity()
 * @ORM\Table(name="cost", indexes={@ORM\Index(name="trip_user_id", columns={"trip_user_id"})})
 */
class Cost {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    protected $id;

//    protected $type;

    /**
     * @ORM\Column(type="integer", name="trip_user_id")
     * @var int
     */
    protected $tripUserId;

    /**
     * @ORM\Column(type="float")
     * @var float
     */
    protected $price;

    /**
     * @ORM\Column(type="string", length=200)
     * @var string
     */
    protected $note;

    /**
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\TripUser")
     * @ORM\JoinColumn(name="trip_user_id", referencedColumnName="id")
     * @var TripUser
     */
    protected $tripUser;

    public function __construct() {
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
    public function getTripUserId() {
        return $this->tripUserId;
    }

    /**
     * @return float
     */
    public function getPrice() {
        return $this->price;
    }

    /**
     * @return string
     */
    public function getNote() {
        return $this->note;
    }

    /**
     * @return TripUser
     */
    public function getTripUser() {
        return $this->tripUser;
    }

    /**
     * @param int $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @param int $tripUserId
     */
    public function setTripUserId($tripUserId) {
        $this->tripUserId = $tripUserId;
    }

    /**
     * @param float $price
     */
    public function setPrice($price) {
        $this->price = $price;
    }

    /**
     * @param string $note
     */
    public function setNote($note) {
        $this->note = $note;
    }

    /**
     * @param TripUser $tripUser
     */
    public function setTripUser($tripUser) {
        $this->tripUser = $tripUser;
    }
}