<?php

namespace Proj\Base\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FormProfileConfig
 *
 * @ORM\Table(name="form_profile_config", indexes={@ORM\Index(name="object_id_class_key", columns={"form_profile_id", "input_name"}), @ORM\Index(name="IDX_A65B361C53D5E2C", columns={"form_profile_id"})})
 * @ORM\Entity
 */
class FormProfileConfig {

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
     * @ORM\Column(name="input_name", type="string", length=255, nullable=true)
     */
    private $inputName;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="text", nullable=true)
     */
    private $value;

    /**
     * @var FormProfile
     *
     * @ORM\ManyToOne(targetEntity="FormProfile", inversedBy="configs")
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
     * Set inputName
     *
     * @param string $inputName
     * @return FormProfileConfig
     */
    public function setInputName($inputName) {
        $this->inputName = $inputName;
        return $this;
    }

    /**
     * Get inputName
     *
     * @return string
     */
    public function getInputName() {
        return $this->inputName;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return FormProfileConfig
     */
    public function setValue($value) {
        $this->value = $value;
        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * Set formProfile
     *
     * @param FormProfile $formProfile
     * @return FormProfileConfig
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
}
