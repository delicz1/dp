<?php

namespace Proj\Base\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FormHelp
 *
 * @ORM\Table(name="form_help")
 * @ORM\Entity
 */
class FormHelp {

    //=====================================================
    //== Konstanty ========================================
    //=====================================================

    const COLUMN_FORM_ID = 'formId';
    const COLUMN_LOCALE = 'locale';
    const COLUMN_INPUT_NAME = 'inputName';

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
     * @ORM\Column(name="form_id", type="integer", nullable=false)
     */
    private $formId;

    /**
     * @var string
     *
     * @ORM\Column(name="locale", type="string", length=10, nullable=false)
     */
    private $locale;

    /**
     * @var string
     *
     * @ORM\Column(name="input_name", type="string", length=255, nullable=false)
     */
    private $inputName;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=false)
     */
    private $content;

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
     * @var integer
     *
     * @ORM\Column(name="edit_time", type="integer", nullable=false)
     */
    private $editTime;

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
     * @return FormHelp
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
     * Set locale
     *
     * @param string $locale
     * @return FormHelp
     */
    public function setLocale($locale) {
        $this->locale = $locale;
        return $this;
    }

    /**
     * Get locale
     *
     * @return string
     */
    public function getLocale() {
        return $this->locale;
    }

    /**
     * Set inputName
     *
     * @param string $inputName
     * @return FormHelp
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
     * Set description
     *
     * @param string $content
     *
     * @return FormHelp
     */
    public function setContent($content) {
        $this->content = $content;
        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Set editTime
     *
     * @param integer $editTime
     * @return FormHelp
     */
    public function setEditTime($editTime) {
        $this->editTime = $editTime;
        return $this;
    }

    /**
     * Get editTime
     *
     * @return integer
     */
    public function getEditTime() {
        return $this->editTime;
    }

    /**
     * Set user
     *
     * @param User $user
     * @return FormHelp
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
}
