<?php

namespace Proj\Base\Object\Form;

use FormItem;
use nil\Html;
use Notificator;
use Proj\Base\Controller\FormController;
use Proj\Base\Entity\Permission;
use Proj\Base\Entity\User;
use Proj\Base\Entity\Group;
use Proj\Base\Entity\FormProfile;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Request;

/**
 * @author necas
 */
class FormProfileAddForm extends DoctrineForm {

    //=====================================================
    //== Konstanty ========================================
    //=====================================================

    const ACTION = FormController::ACTION_ADD;
    const NAME = 'formProfileAdd';

    const INPUT_CONFIG_PREFIX = '__p_';
    const INPUT_FORM_ID = '__profile_fid';
    const INPUT_NAME = '__profile_name';
    const INPUT_VIEW = '__profile_v';
    const INPUT_DELETE = '__profile_e';

    const SUBMIT = 'save';

    /**
     * @var User
     */
    private $selfUser = null;
    /**
     * @var Group
     */
    private $group = null;

    //=====================================================
    //== Konfigurace ======================================
    //=====================================================

    /**
     * @param User     $selfUser
     * @param Group    $group
     * @param Registry $doctrine
     * @param \FormatterInterface $formater
     * @param Request  $request
     *
     * @return FormProfileAddForm
     */
    public static function create(User $selfUser = null, Group $group = null, Registry $doctrine = null, $formater = null, Request $request = null) {
        $form = new self(self::NAME, self::ACTION, self::POST);
        $form->setFormater($formater);
        $form->setDoctrine($doctrine);
        $form->addSubmit(self::SUBMIT, 'form.save', 'icon-system-20x20 save');
        if ($request instanceof Request) {
            $form->selfUser = $selfUser;
            $form->group = $group;
            $form->setRequest($request);
            $form->setHelpManager(false);       // HERE TEST PERMISSION
            $form->init();
        }

        return $form;
    }

    protected function init() {
        $request = $this->getRequest();
        $params = $request->postParams();
        foreach ($params as $paramName => $paramValue) {
            if ($this->isProfileParam($paramName)) {
                $this->addHidden($paramName, $paramValue);
            }
        }

        $this->addHidden(self::INPUT_FORM_ID, $request->getParam(self::INPUT_FORM_ID))->addRuleRequired('');
        $this->addText(self::INPUT_NAME, 'form.profile.name', '')
            ->addRuleRequired('')
            ->setWidth(120)
            ->setTranslate(false);
        $this->addSelect(self::INPUT_VIEW, 'form.profile.can.view', null, $this->getOptionsPermission())->setWidth(120);
        $this->addSelect(self::INPUT_DELETE, 'form.profile.can.delete', null, $this->getOptionsPermission())->setWidth(120);

        $this->handle();
    }

    public function onSuccess() {
        $tr = $this->getTranslator();

        /** @var FormProfileAddForm|FormItem[] $this */
        $profile = new FormProfile();
        $profile->setName($this[self::INPUT_NAME]->getValue());
        $profile->setUserId($this->selfUser->getId());
        $profile->setFormId($this[self::INPUT_FORM_ID]->getValue());
        $profile->setVObjType($this[self::INPUT_VIEW]->getValue());
        $profile->setVObjId($this->getObjIdByType($this[self::INPUT_VIEW]->getValue()));
        $profile->setEObjType($this[self::INPUT_DELETE]->getValue());
        $profile->setEObjId($this->getObjIdByType($this[self::INPUT_DELETE]->getValue()));
        $profileConfigItems = [];
        foreach ($this->items as $item) {
            if ($this->isProfileParam($item->getName())) {
                $profileConfigItems[preg_replace('/^' . self::INPUT_CONFIG_PREFIX .
                                                 '/', '', $item->getName())] = $item->getValue();
            }
        }
        foreach ($profileConfigItems as $profileConfigName => $profileConfigValue) {
            $profile->setConfig($profileConfigName, $profileConfigValue);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($profile);
        $em->flush();
        Notificator::add2(Notificator::TYPE_SUCCESS, $tr->get('form.saved'), '', 'icon-form-20x20 profile-add');
        echo Html::el('script')->setHtml(FormProfileAddDialog::close());
    }

    public function onUpdate() {
        Notificator::add('UPDATE', '', Notificator::TYPE_INFO);
    }

    public function onError() {
        Notificator::add('ERROR', '', Notificator::TYPE_ERROR);
    }

    //=====================================================
    //== Options ==========================================
    //=====================================================

    /**
     * @return array
     */
    private function getOptionsPermission() {
        $options = [
            FormProfile::TYPE_USER => 'form.profile.me', FormProfile::TYPE_GROUP => 'form.profile.group',
        ];

        return $options;
    }

    /**
     * @param int $objType
     *
     * @return int|null
     */
    private function getObjIdByType($objType) {
        $objId = null;
        switch ($objType) {
            case FormProfile::TYPE_USER:
                $objId = $this->selfUser->getId();
                break;
            case FormProfile::TYPE_GROUP:
                $objId = $this->group->getId();
                break;
        }

        return $objId;
    }

    /**
     * @param string $name
     *
     * @return int
     */
    private function isProfileParam($name) {
        return preg_match('/^' . self::INPUT_CONFIG_PREFIX . '.*$/', $name);
    }

    //=====================================================
    //== Validace =========================================
    //=====================================================
}