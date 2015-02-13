<?php
/**
 * @author springer
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class User
 * @package AppBundle\Entity
 * @ORM\Entity()
 * @ORM\Table(name="user", indexes={@ORM\Index(name="ldap_id", columns={"ldap_id"})})
 */
class User {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="string", name="login", length=50)
     * @var string
     */
    protected $login;

    /**
     * @ORM\Column(type="string", length=50)
     * @var
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length=50)
     * @var string
     */
    protected $firstname;

    /**
     * @ORM\Column(type="string", length=75)
     * @var string
     */
    protected $lastname;
    /**
     * @ORM\Column(type="string", length=100)
     * @var string
     */
    protected $email;

    /**
     * @ORM\Column(type="string", name="ldap_id", length=100)
     * @var string
     */
    protected $ldapId;


    /**
     * @ORM\OneToMany(targetEntity="TripUser", mappedBy="userId")
     * @var TripUser
     */
    protected $tripUser;

    public function __construct() {
        $this->tripUser = new ArrayCollection();
    }


    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLogin() {
        return $this->login;
    }

    /**
     * @return mixed
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getFirstname() {
        return $this->firstname;
    }

    /**
     * @return string
     */
    public function getLastname() {
        return $this->lastname;
    }

    /**
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getLdapId() {
        return $this->ldapId;
    }

    /**
     * @param int $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @param string $login
     */
    public function setLogin($login) {
        $this->login = $login;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname($firstname) {
        $this->firstname = $firstname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname($lastname) {
        $this->lastname = $lastname;
    }

    /**
     * @param string $email
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * @param string $ldapId
     */
    public function setLdapId($ldapId) {
        $this->ldapId = $ldapId;
    }
}