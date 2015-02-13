<?php
/**
 * @author springer
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class TripUser
 * @package AppBundle\Entity
 * @ORM\Entity()
 * @ORM\Table(name="trip_user")
 */
class TripUser {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="integer", name="user_id")
     * @var int
     */
    protected $userId;

    /**
     * @ORM\Column(type="integer", name="trip_id")
     * @var int
     */
    protected $tripId;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="tripUser")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @var User
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Trip")
     * @ORM\JoinColumn(name="trip_id", referencedColumnName="id")
     * @var Trip
     */
    protected $trip;

}