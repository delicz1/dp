<?php

use Proj\Base\Entity\FormProfile;
use Proj\Base\Entity\FormProfileDefault;
use Proj\Base\Object\Form\FormProfileAddDialog;
use Proj\Base\Object\Form\FormProfileAddForm;
use Proj\Base\Object\Form\FormProfileDeleteDialog;
use Proj\Base\Object\Form\DoctrineForm;
use Proj\Base\Object\Form\DoctrineFormTabs;

/**
 * @author necas
 */
class FormProfileUtil {

    /**
     * @param DoctrineForm|DoctrineFormTabs|Form $form
     * @param array $profileCriteriaArray


*
*@return \Proj\Base\Entity\FormProfile[]
     */
    public static function getProfileViewList($form, $profileCriteriaArray) {
        $inWhere = [];
        $where = Criteria::equal('fp.' . FormProfile::COLUMN_FORM_ID, $form->getId()) . ' AND ';
        foreach ($profileCriteriaArray as $objectType => $id) {
            $inWhere[] = '(' . Criteria::equal('fp.' . FormProfile::COLUMN_V_OBJ_TYPE, $objectType) . ' AND ' . Criteria::equal('fp.' . FormProfile::COLUMN_V_OBJ_ID, $id) . ')';
        }
        $where .= '(' . implode(' OR ', $inWhere) . ')';
        /** @var \Doctrine\ORM\EntityRepository $repo */
        $repo = $form->getDoctrine()->getRepository('ProjBaseBundle:FormProfile');
        $profiles = $repo->createQueryBuilder('fp')
            ->where($where)
            ->getQuery()
            ->getResult();
        return $profiles;
    }

    /**
     * @param DoctrineForm|DoctrineFormTabs|Form $form
     * @param $profileCriteriaArray

*
*@return string[]
     */
    public static function getProfileViewArray($form, $profileCriteriaArray) {
        /** @var \Doctrine\ORM\EntityRepository $repo */
        $repo = $form->getDoctrine()->getRepository('ProjBaseBundle:User');
        /** @var \Proj\Base\Entity\User $user */
        $user = $repo->find($profileCriteriaArray[FormProfile::TYPE_USER]);
        $tr = $form->getTranslator();
        $profileList = self::getProfileViewList($form, $profileCriteriaArray);
        /** @var string[] $optionsProfile */
        $options = ['' => ''];
        $optionsProfileMy = [];
        $optionsProfileShared = [];
        /** @var \Doctrine\ORM\EntityRepository $repo */
        $repo = $form->getDoctrine()->getRepository('ProjBaseBundle:FormProfileDefault');
        $default = $repo->findOneBy([
            FormProfileDefault::COLUMN_FORM_ID => $form->getId(),
            FormProfileDefault::COLUMN_USER => $user
        ]);
        $defaultId = 0;
        if ($default instanceof FormProfileDefault) {
            $defaultId = $default->getFormProfile()->getId();
        }
        foreach ($profileList as $profile) {
            $label = $profile->getName();

            if ($profile->getId() == $defaultId) {
                $label .= ' (' . $form->getTranslator()->get('form.profile.default') . ')';
            }
            if ($profile->getVObjType() == FormProfile::TYPE_USER) {
                $optionsProfileMy[$profile->getId()] = $label;
            } else {
                $optionsProfileShared[$profile->getId()] = $label;
            }
        }
        if (count($optionsProfileMy)) {
            $options[$tr->get('form.profile.my')] = $optionsProfileMy;
        }
        if (count($optionsProfileShared)) {
            $options[$tr->get('form.profile.shared')] = $optionsProfileShared;
        }
        return $options;
    }

    /**
     * @param DoctrineForm|DoctrineFormTabs|Form $form
     * @param array $profileCriteriaArray
     * @return FormProfile[]
     */
    public static function getProfileDeleteList($form, $profileCriteriaArray) {
        $inWhere = [];
        $where = Criteria::equal('fp.' . FormProfile::COLUMN_FORM_ID, $form->getId()) . ' AND ';
        foreach ($profileCriteriaArray as $objectType => $id) {
            $inWhere[] = '(' . Criteria::equal('fp.' . FormProfile::COLUMN_E_OBJ_TYPE, $objectType) . ' AND ' . Criteria::equal('fp.' . FormProfile::COLUMN_E_OBJ_ID, $id) . ')';
        }
        $where .= '(' . implode(' OR ', $inWhere) . ')';
        /** @var \Doctrine\ORM\EntityRepository $repo */
        $repo = $form->getDoctrine()->getRepository('ProjBaseBundle:FormProfile');
        $profiles = $repo->createQueryBuilder('fp')
                         ->where($where)
                         ->getQuery()
                         ->getResult();
        return $profiles;
    }

    /**
     * @param DoctrineForm|DoctrineFormTabs|Form $form
     * @param $profileCriteriaArray
     * @return string[]
     */
    public static function getProfileDeleteArray($form, $profileCriteriaArray) {
        $profileList = self::getProfileDeleteList($form, $profileCriteriaArray);
        /** @var string[] $optionsProfile */
        $optionsProfile = ['' => ''];
        foreach ($profileList as $profile) {
            $optionsProfile[$profile->getId()] = $profile->getName();
        }
        return $optionsProfile;
    }

    /**
     * @param DoctrineForm|DoctrineFormTabs|Form $form
     * @param array $profileCriteriaArray


*
*@return FormProfileDefaultInterface|\Proj\Base\Entity\FormProfileDefault
     */
    public static function getProfileDefault($form, $profileCriteriaArray) {
        $userId = $profileCriteriaArray[FormProfile::TYPE_USER];
        $formId = $form->getId();
        /** @var \Doctrine\ORM\EntityRepository $repo */
        $repo = $form->getDoctrine()->getRepository('ProjBaseBundle:User');
        /** @var \Proj\Base\Entity\User $user */
        $user = $repo->find($userId);

        /** @var \Doctrine\ORM\EntityRepository $repo */
        $repo = $form->getDoctrine()->getRepository('ProjBaseBundle:FormProfileDefault');
        $profileDefault = $repo->findOneBy([
            FormProfileDefault::COLUMN_FORM_ID => $formId,
            FormProfileDefault::COLUMN_USER => $user
        ]);
        if (!$profileDefault instanceof FormProfileDefault) {
            $profileDefault = new FormProfileDefault();
            $profileDefault->setUser($user);
            $profileDefault->setFormId($formId);
        }
        return $profileDefault;
    }

    /**
     * @param DoctrineForm|DoctrineFormTabs|Form $form
     * @param array $profileCriteriaArray
     * @param int  $id
     */
    public static function saveProfileDefault($form, $profileCriteriaArray, $id) {
        $profileDefault = self::getProfileDefault($form, $profileCriteriaArray);
        $em = $form->getDoctrine()->getManager();
        if ($id == 0 && $profileDefault->getId()) {
            $em->remove($profileDefault);
        } else if ($id != 0) {
            /** @var \Doctrine\ORM\EntityRepository $repo */
            $repo = $form->getDoctrine()->getRepository('ProjBaseBundle:FormProfile');
            /** @var \Proj\Base\Entity\FormProfile $formProfile */
            $formProfile = $repo->find($id);
            $profileDefault->setFormProfile($formProfile);
            $em->persist($profileDefault);
        }
        $em->flush();
    }

    /**
     * @param $id
     * @param DoctrineForm|DoctrineFormTabs|Form $form


*
*@return FormProfile
     */
    public static function getProfile($id, $form) {
        /** @var \Doctrine\ORM\EntityRepository $repo */
        $repo = $form->getDoctrine()->getRepository('ProjBaseBundle:FormProfile');
        return $repo->find($id);
    }

    /**
     * @param DoctrineForm|DoctrineFormTabs|Form $form

*
*@return Dialog
     */
    public static function getAddDialog($form) {
        return FormProfileAddDialog::create($form);
    }

    /**
     * @param DoctrineForm|DoctrineFormTabs|Form $form
     * @param FormProfileInterface|\Proj\Base\Entity\FormProfile $profile


*
*@return Dialog
     */
    public static function getDeleteDialog($form, FormProfile $profile) {
        return FormProfileDeleteDialog::create($form, $profile);
    }

    /**
     * @param DoctrineFormTabs|DoctrineForm|Form $form
     * @param array $profileCriteriaArray
     * @param string $name
     */
    public static function saveAsDefaultProfile($form, $profileCriteriaArray, $name) {
        $profileDefault = self::getProfileDefault($form, $profileCriteriaArray);
        $em = $form->getDoctrine()->getManager();
        if ($profileDefault->getFormProfileId() > 0) {
            /** @var \Doctrine\ORM\EntityRepository $repo */
            $repo = $form->getDoctrine()->getRepository('ProjBaseBundle:FormProfile');
            $oldProfile = $repo->find($profileDefault->getFormProfileId());
            $em->remove($oldProfile);
        }
        $userId = $profileCriteriaArray[FormProfile::TYPE_USER];
        /** @var FormProfileAddForm|FormItem[] $this */
        $profile = new FormProfile();
        $profile->setName($name);
        $profile->setUserId($profileCriteriaArray[FormProfile::TYPE_USER]);
        $profile->setFormId($form->getId());
        $profile->setVObjType(FormProfile::TYPE_USER);
        $profile->setVObjId($userId);
        $profile->setEObjType(FormProfile::TYPE_USER);
        $profile->setEObjId($userId);
        $profileConfigItems = [];
        foreach ($form->items as $item) {
            if ($item->getProfile() || $item->getProfileIf()) {
                $value = $item->getValue();
                if (is_callable($item->profileEncode)) {
                    $fn = $item->profileEncode;
                    $value = $fn($value);
                }
                $profileConfigItems[$item->getName()] = serialize($value);
            }
        }
        foreach ($profileConfigItems as $profileConfigName => $profileConfigValue) {
            $profile->setConfig($profileConfigName, $profileConfigValue);
        }
        $em->persist($profile);
        $profileDefault->setFormProfile($profile);
        $em->persist($profileDefault);
        $em->flush();
    }
}