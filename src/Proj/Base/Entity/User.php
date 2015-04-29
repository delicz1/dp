<?php

namespace Proj\Base\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Proj\BussinesTrip\Entity\TripUser;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 *
 * @ORM\Table(name="user", indexes={@ORM\Index(name="email", columns={"email"})})
 * @ORM\Entity
 */
class User implements UserInterface, \Serializable {

    //=====================================================
    //== Konstanty ========================================
    //=====================================================

    const COLUMN_EMAIL = 'email';
    const COLUMN_PASSWD = 'passwd';
    const COLUMN_NAME = 'name';
    const COLUMN_SURNAME = 'surname';
    const COLUMN_STATUS = 'status';
    const COLUMN_ROLE = 'role';
    const COLUMN_ADDRESS = 'address';

    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    const STATUS_ACTIVE_TRANS = 'vehicle.status.1';
    const STATUS_DELETED_TRANS = 'vehicle.status.2';

    const ROLE_USER = 'ROLE_USER';
    const ROLE_ACCOUNTANT = 'ROLE_ACCOUNTANT';
    const ROLE_ADMIN = 'ROLE_ADMIN';

    //=====================================================
    //== ORM ==============================================
    //=====================================================

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="passwd", type="string", length=40, nullable=false)
     */
    private $passwd;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=40, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=40, nullable=false)
     */
    private $surname;

    /**
     * @var string
     * @ORM\Column(name="email", type="string", length=100, nullable=false, unique=true)
     */
    private $email;

    /**
     * @var int
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @var string
     * @ORM\Column(name="role", type="string", length=100, nullable=false)
     */
    private $role;

    /**
     * @var string
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @var ArrayCollection|TripUser[]
     * @ORM\OneToMany(targetEntity="Proj\BussinesTrip\Entity\TripUser", mappedBy="user", cascade={"persist"})
     */
    protected $tripUsers;

    public static $statusList = [
        self::STATUS_ACTIVE => self::STATUS_ACTIVE_TRANS,
        self::STATUS_DELETED => self::STATUS_DELETED_TRANS
    ];

    //=====================================================
    //== Konstruktor ======================================
    //=====================================================

    /**
     *
     */
    public function __construct() {
        $this->roles = new ArrayCollection();
        $this->accessTemplateUsers = new ArrayCollection();
        $this->tripUsers = new ArrayCollection();
    }

    //=====================================================
    //== Set/Get ==========================================
    //=====================================================

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set passwd
     *
     * @param string $passwd
     * @return User
     */
    public function setPasswd($passwd) {
        $this->passwd = $passwd;
        return $this;
    }

    /**
     * Get passwd
     *
     * @return string
     */
    public function getPasswd() {
        return $this->passwd;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return User
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
        return (string) $this->name;
    }

    /**
     * Set surname
     *
     * @param string $surname
     * @return User
     */
    public function setSurname($surname) {
        $this->surname = $surname;
        return $this;
    }

    /**
     * Get surname
     *
     * @return string
     */
    public function getSurname() {
        return (string) $this->surname;
    }

    /**
     * @param string $email
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail()  {
        return $this->email;
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
     * @return string
     */
    public function getRole() {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole($role) {
        $this->role = $role;
    }

    /**
     * @return string
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress($address) {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getFullName() {
        return ($this->surname || $this->name) ? $this->surname . ' ' . $this->name : $this->email;
    }

    /**
     * @return ArrayCollection|\Proj\BussinesTrip\Entity\TripUser[]
     */
    public function getTripUsers() {
        return $this->tripUsers;
    }

    /**
     * @param ArrayCollection|\Proj\BussinesTrip\Entity\TripUser[] $tripUsers
     */
    public function setTripUsers($tripUsers) {
        $this->tripUsers = $tripUsers;
    }

    //=====================================================
    //== Security =========================================
    //=====================================================

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * String representation of object
     *
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     */
    public function serialize() {
        return serialize([
            $this->id,
            $this->email,
            $this->passwd,
            // see section on salt below
            // $this->salt,
        ]);
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Constructs the object
     *
     * @link http://php.net/manual/en/serializable.unserialize.php
     *
     * @param string $serialized <p>
     *                           The string representation of the object.
     *                           </p>
     *
     * @return void
     */
    public function unserialize($serialized) {
        list (
            $this->id,
            $this->email,
            $this->passwd,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
    }

    /**
     * Returns the roles granted to the user.
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return string[] The user roles
     */
    public function getRoles() {
        return [$this->getRole()];
    }

    /**
     * Returns the password used to authenticate the user.
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword() {
        return $this->getPasswd();
    }

    /**
     * Returns the salt that was originally used to encode the password.
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt() {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername() {
        return $this->email;
    }

    /**
     * Removes sensitive data from the user.
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials() {}

    //=====================================================
    //== toString =========================================
    //=====================================================

    /**
     * @return string
     */
    public function __toString() {
        return $this->id . ' ' . $this->email . '(' . $this->name . ' ' . $this->surname . ')';
    }
}
