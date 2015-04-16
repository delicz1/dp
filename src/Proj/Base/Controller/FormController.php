<?php

namespace Proj\Base\Controller;

use Proj\Base\Entity\FormHelp;
use Proj\Base\Object\Form\FormHelpAddForm;
use Proj\Base\Object\Form\FormProfileAddForm;
use Notificator;
use /** @noinspection PhpUnusedAliasInspection */
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use /** @noinspection PhpUnusedAliasInspection */
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Proj\Base\Entity\FormProfile;
use Symfony\Component\HttpFoundation\Request;
use UploadHandler;
use UploadHandler2;

/**
 * @Route("/form", name="_form")
 * @Template()
 */
class FormController extends BaseController {

    const ACTION_ADD = '/form/add';
    const ACTION_DELETE = '/form/delete';
    const ACTION_UPLOAD = '/form/upload';
    const ACTION_UPLOAD2 = '/form/upload2';
    const ACTION_UPLOAD_LINK = '';      // Doimplementovat
    const ACTION_UPLOAD_WYSIWYG = '';   // Doimplementovat
    const ACTION_HELP_ADD = '/form/helpAdd';

    /**
     * @Route("/add")
     *
     */
    public function addAction() {
        $selfUser = $this->getSelfUser();
        $form = FormProfileAddForm::create($selfUser, null, $this->getDoctrine(), $this->getFormater(), $this->getRequestNil());
        echo $form;
        exit;
    }

    /**
     * @Route("/delete")
     * @param Request $request
     */
    public function deleteAction(Request $request) {
        $id = $request->get('id');
        /** @var \Proj\Base\Entity\FormProfile $profile */
        $profile = $this->getDoctrine()->getRepository('ProjBaseBundle:FormProfile')->find($id);
        $selfUser = $this->getSelfUser();

        $canDelete = false;
        $objType = $profile->getEObjType();
        $objId = $profile->getEObjId();
        if ($objType == FormProfile::TYPE_USER && $objId == $selfUser->getId()) {
            $canDelete = true;
        }
        if ($canDelete) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($profile);
            $em->flush();
        }
        Notificator::add2(Notificator::TYPE_SUCCESS, $this->getLangTranslator()->get('form.deleted'), '', 'icon-form-20x20 profile-delete');
        exit;
    }

    /**
     * @Route("/upload")
     */
    public function uploadAction() {
        new UploadHandler();
        exit;
    }

    /**
     * @Route("/upload2")
     */
    public function upload2Action() {
        (new UploadHandler2())->run();
        exit;
    }

    /**
     * @Route("/helpAdd")
     * @param Request $request
     */
    public function helpAddAction(Request $request) {
        $formId = $request->get(FormHelpAddForm::INPUT_FORM_ID);
        $locale = $request->get(FormHelpAddForm::INPUT_LOCALE);
        $inputName = $request->get(FormHelpAddForm::INPUT_INPUT_NAME);
        $doctrine = $this->getDoctrine();
        $help = $doctrine->getRepository('ProjBaseBundle:FormHelp')->findOneBy([
            FormHelp::COLUMN_FORM_ID => $formId,
            FormHelp::COLUMN_LOCALE => $locale,
            FormHelp::COLUMN_INPUT_NAME => $inputName
        ]);
        if (!$help instanceof FormHelp) {
            $help = new FormHelp();
            $help->setFormId($formId);
            $help->setLocale($locale);
            $help->setInputName($inputName);
        }
        $selfUser = $this->getSelfUser();
        $form = FormHelpAddForm::create($help, $selfUser, $doctrine, $this->getFormater(), $this->getRequestNil());
        echo $form;
        exit;
    }
}