<?php

namespace Proj\Base\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * Role
 *
 * @ORM\Table(name="role")
 * @ORM\Entity
 */
class Role implements RoleInterface {

    const SUPERADMIN = 1;
    const ADMIN = 2;
    const USER = 3;

    const COLUMN_TITLE = 'title';

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
     * @ORM\Column(name="title", type="string", length=30, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=20, nullable=false)
     */
    private $role;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="roles")
     */
    private $users;

    //=====================================================
    //== Konstruktor ======================================
    //=====================================================

    /**
     *
     */
    public function __construct() {
        $this->users = new ArrayCollection();
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
     * Set name
     *
     * @param string $title
     * @return Role
     */
    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set role
     *
     * @param string $role
     * @return Role
     */
    public function setRole($role) {
        $this->role = $role;
        return $this;
    }

    /**
     * Get role
     *
     * @return string
     */
    public function getRole() {
        return $this->role;
    }

    /**
     * Add users
     *
     * @param User $users
     * @return Role
     */
    public function addUser(User $users) {
        $this->users[] = $users;
        return $this;
    }

    /**
     * Remove users
     *
     * @param User $users
     */
    public function removeUser(User $users) {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return ArrayCollection|User[]
     */
    public function getUsers() {
        return $this->users;
    }

    //=====================================================
    //== toString =========================================
    //=====================================================

    /**
     * @return string
     */
    public function __toString() {
        return $this->id . ' ' . $this->title . '(' . $this->role . ')';
    }
}
