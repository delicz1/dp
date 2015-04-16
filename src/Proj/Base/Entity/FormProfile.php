<?php

namespace Proj\Base\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * FormProfile
 *
 * @ORM\Table(name="form_profile", indexes={@ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity
 */
class FormProfile implements \FormProfileInterface {

    //=====================================================
    //== Konstanty ========================================
    //=====================================================

    const TYPE_USER = 1;
    const TYPE_GROUP = 2;

    const COLUMN_USER_ID = 'userId';
    const COLUMN_FORM_ID = 'formId';
    const COLUMN_V_OBJ_TYPE = 'vObjType';
    const COLUMN_V_OBJ_ID = 'vObjId';
    const COLUMN_E_OBJ_TYPE = 'eObjType';
    const COLUMN_E_OBJ_ID = 'eObjId';

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
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=true)
     */
    private $userId;

    /**
     * @var integer
     *
     * @ORM\Column(name="form_id", type="integer", nullable=true)
     */
    private $formId;

    /**
     * @var integer
     *
     * @ORM\Column(name="v_obj_type", type="integer", nullable=true)
     */
    private $vObjType;

    /**
     * @var integer
     *
     * @ORM\Column(name="v_obj_id", type="integer", nullable=true)
     */
    private $vObjId;

    /**
     * @var integer
     *
     * @ORM\Column(name="e_obj_type", type="integer", nullable=true)
     */
    private $eObjType;

    /**
     * @var integer
     *
     * @ORM\Column(name="e_obj_id", type="integer", nullable=true)
     */
    private $eObjId;

    /**
     * @var ArrayCollection|FormProfileConfig[]
     * @ORM\OneToMany(targetEntity="FormProfileConfig", mappedBy="formProfile", cascade={"persist", "remove"}, indexBy="inputName")
     */
    protected $configs;

    //=====================================================
    //== Konstruktor ======================================
    //=====================================================

    /**
     *
     */
    public function __construct() {
        $this->configs = new ArrayCollection();
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
     * @param string $name
     * @return FormProfile
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
        return $this->name;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return FormProfile
     */
    public function setUserId($userId) {
        $this->userId = $userId;
        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId() {
        return $this->userId;
    }

    /**
     * Set formId
     *
     * @param integer $formId
     * @return FormProfile
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
     * Set vObjType
     *
     * @param integer $vObjType
     * @return FormProfile
     */
    public function setVObjType($vObjType) {
        $this->vObjType = $vObjType;
        return $this;
    }

    /**
     * Get vObjType
     *
     * @return integer
     */
    public function getVObjType() {
        return $this->vObjType;
    }

    /**
     * Set vObjId
     *
     * @param integer $vObjId
     * @return FormProfile
     */
    public function setVObjId($vObjId) {
        $this->vObjId = $vObjId;
        return $this;
    }

    /**
     * Get vObjId
     *
     * @return integer
     */
    public function getVObjId() {
        return $this->vObjId;
    }

    /**
     * Set eObjType
     *
     * @param integer $eObjType
     * @return FormProfile
     */
    public function setEObjType($eObjType) {
        $this->eObjType = $eObjType;
        return $this;
    }

    /**
     * Get eObjType
     *
     * @return integer
     */
    public function getEObjType() {
        return $this->eObjType;
    }

    /**
     * Set eObjId
     *
     * @param integer $eObjId
     * @return FormProfile
     */
    public function setEObjId($eObjId) {
        $this->eObjId = $eObjId;
        return $this;
    }

    /**
     * Get eObjId
     *
     * @return integer
     */
    public function getEObjId() {
        return $this->eObjId;
    }

    /**
     * Add config
     *
     * @param FormProfileConfig $config
     * @return FormProfile
     */
    public function addConfig(FormProfileConfig $config) {
        $config->setFormProfile($this);
        $this->configs[] = $config;
        return $this;
    }

    /**
     * Remove config
     *
     * @param FormProfileConfig $config
     */
    public function removeConfig(FormProfileConfig $config) {
        $config->setFormProfile(null);
        $this->configs->removeElement($config);
    }

    /**
     * Get configs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getConfigs() {
        return $this->configs;
    }


    /**
     * @param $key
     * @return mixed
     */
    public function getConfig($key) {
        return isset($this->configs[$key]) ? $this->configs[$key]->getValue() : '';
    }

    /**
     * @param $key
     * @param $value
     *
     * @return $this
     */
    public function setConfig($key, $value) {
        if ('' === $value) {
            if (isset($this->configs[$key])) {
                unset($this->configs[$key]);
            }
        } else {
            if (isset($this->configs[$key])) {
                $this->configs[$key]->setValue($value);
            } else {
                $config = (new FormProfileConfig())
                    ->setInputName($key)
                    ->setValue($value);
                $this->addConfig($config);
            }
        }
        return $this;
    }

    //=====================================================
    //== FormProfileInterface =============================
    //=====================================================

    /**
     * @param \FormItem[] $items
     */
    public function initConfigKeys($items) {}

    /**
     * @param int $objectId Id objektu ktery ma nastaveny defaultni profil (userid)
     *
     * @return bool
     */
    public function isDefault($objectId) {
        return false;
    }
}
