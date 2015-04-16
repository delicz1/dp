<?php

namespace Proj\Base\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FormProfileDefault
 *
 * @ORM\Table(name="form_profile_default", indexes={@ORM\Index(name="user_id_form_id", columns={"user_id", "form_id"}), @ORM\Index(name="form_profile_id", columns={"form_profile_id"}), @ORM\Index(name="IDX_8086839AA76ED395", columns={"user_id"})})
 * @ORM\Entity
 */
class FormProfileDefault {

    //=====================================================
    //== Konstanty ========================================
    //=====================================================

    const COLUMN_FORM_ID = 'formId';
    const COLUMN_USER = 'user';

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
     * @var integer
     *
     * @ORM\Column(name="form_id", type="integer", nullable=true)
     */
    private $formId;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var FormProfile
     *
     * @ORM\ManyToOne(targetEntity="FormProfile")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="form_profile_id", referencedColumnName="id")
     * })
     */
    private $formProfile;

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
     * Set formId
     *
     * @param integer $formId
     * @return FormProfileDefault
     */
    public function setFormId($formId) {
        $this->formId = $formId;
        return $this;
    }

    /**
     * Get formId
     *
     * @return integer
     */
    public function getFormId() {
        return $this->formId;
    }

    /**
     * Set formProfile
     *
     * @param FormProfile $formProfile
     * @return FormProfileDefault
     */
    public function setFormProfile(FormProfile $formProfile = null) {
        $this->formProfile = $formProfile;
        return $this;
    }

    /**
     * Get formProfile
     *
     * @return FormProfile
     */
    public function getFormProfile() {
        return $this->formProfile;
    }

    /**
     * Set user
     *
     * @param User $user
     * @return FormProfileDefault
     */
    public function setUser(User $user = null) {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * @return int
     */
    public function getFormProfileId() {
        $fp = $this->getFormProfile();
        $fpId = 0;
        if ($fp instanceof FormProfile) {
            $fpId = $fp->getId();
        }
        return $fpId;
    }
}
