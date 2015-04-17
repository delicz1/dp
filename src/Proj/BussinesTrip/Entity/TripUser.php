<?php
/**
 * @author springer
 */

namespace Proj\BussinesTrip\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Proj\Base\Entity\User;

/**
 * Class TripUser
 * @package Proj\BussinesTrip\Entity
 * @ORM\Entity
 * @ORM\Table(name="trip_user",
 *    indexes={@ORM\Index(name="user", columns={"user_id"})},
 *    uniqueConstraints={@ORM\UniqueConstraint(name="trip_user", columns={"trip_id", "user_id"})}
 * )
 */
class TripUser {

    //=====================================================
    //== Konstanty ========================================
    //=====================================================

    const COLUMN_ID = 'id';
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
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @var Trip
     * @ORM\ManyToOne(targetEntity="Trip", inversedBy="id")
     * @ORM\JoinColumns(
     *   @ORM\JoinColumn(name="trip_id", referencedColumnName="id")
     * )
     */
    protected $trip;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="Proj\Base\Entity\User", inversedBy="id")
     * @ORM\JoinColumns(
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * )
     */
    protected $user;

    /**
     * @var ArrayCollection|Expense[]
     * @ORM\OneToMany(targetEntity="Expense", mappedBy="tripUser", cascade={"persist"})
     */
    protected $expenses;

    //=====================================================
    //== Konstruktor ======================================
    //=====================================================

    /**
     * Konstruktor
     */
    public function __construct() {
        $this->expenses = new ArrayCollection();
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

    /**
     * @return User
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user) {
        $this->user = $user;
    }

    /**
     * @return ArrayCollection|Expense[]
     */
    public function getExpenses() {
        return $this->expenses;
    }

    /**
     * @param ArrayCollection|Expense[] $expenses
     */
    public function setExpenses($expenses) {
        $this->expenses = $expenses;
    }

}