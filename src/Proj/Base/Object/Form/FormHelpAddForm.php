<?php

namespace Proj\Base\Object\Form;

use Doctrine\Bundle\DoctrineBundle\Registry;
use nil\Html;
use Proj\Base\Controller\FormController;
use Proj\Base\Entity\FormHelp;
use Proj\Base\Entity\Permission;
use Proj\Base\Entity\User;
use Proj\Base\Object\Locale\SystemLang;
use Request;
use Notificator;

/**
 *
 */
class FormHelpAddForm extends DoctrineForm {

    //=====================================================
    //== Konstanty ========================================
    //=====================================================

    const ACTION = FormController::ACTION_HELP_ADD;
    const NAME = 'formHelpAdd';

    const INPUT_CONTENT = '__help_content';
    const INPUT_LOCALE = '__locale';
    const INPUT_FORM_ID = '__help_fid';
    const INPUT_INPUT_NAME = '__input_name';

    const SUBMIT = 'save';

    /**
     * @var FormHelp
     */
    private $help;

    /**
     * @var User
     */
    private $selfUser = null;

    //=====================================================
    //== Konfigurace ======================================
    //=====================================================

    /**
     * @param FormHelp $help
     * @param User     $selfUser
     * @param Registry $doctrine
     * @param \FormatterInterface $formater
     * @param Request  $request
     *
     * @return FormProfileAddForm
     */
    public static function create(FormHelp $help = null, User $selfUser = null, Registry $doctrine = null, $formater = null, Request $request = null) {
        $form = new self(self::NAME, self::ACTION, self::POST);
        $form->setFormater($formater);
        $form->setDoctrine($doctrine);
        $form->addSubmit(self::SUBMIT, 'form.save', 'icon-system-20x20 save');
        if ($request instanceof Request) {
            $form->help = $help;
            $form->selfUser = $selfUser;
            $form->setRequest($request);
            $form->setHelpManager(false);       // HERE TEST PERMISSION
            $form->init();
        }

        return $form;
    }

    protected function init() {
        $help = $this->help;

        $this->addHidden(self::INPUT_FORM_ID, $help->getFormId());
        $this->addHidden(self::INPUT_INPUT_NAME, $help->getInputName());
        $this->addSelect(self::INPUT_LOCALE, 'form.help.locale', $help->getLocale(), $this->getOptionsLocale())
            ->addAttr('onchange', $this->getHandler()->updateDeselected([self::INPUT_CONTENT]));
        $this->addTextArea(self::INPUT_CONTENT, 'form.help.content', $help->getContent())
            ->addAttr('rows', 10)
            ->setWidth(240)
            ->setHeight(200)
            ->setTranslate(false);

        $this->handle();
    }

    public function onSuccess() {
        $tr = $this->getTranslator();

        $help = $this->help;
        $help->setContent($this[self::INPUT_CONTENT]->getValue());
        $help->setUser($this->selfUser);
        $help->setEditTime(time());

        $em = $this->getDoctrine()->getManager();
        if ($help->getContent() != '') {
            $em->persist($help);
        } else if ($help->getId()) {
            $em->remove($help);
        }
        $em->flush();

        Notificator::add2(Notificator::TYPE_SUCCESS, $tr->get('form.saved'), '', 'icon-form-20x20 profile-add');
        echo Html::el('script')->setHtml(FormHelpAddDialog::close());
    }

    //=====================================================
    //== Options ==========================================
    //=====================================================

    /**
     * @return array
     */
    private function getOptionsLocale() {
        $options = [];
        $langArr = SystemLang::getLangArr();
        foreach ($langArr as $langIso => $langParamArr) {
            $langNative = $langParamArr[SystemLang::NATIVE];
            $options[$langIso] = [
                \FormItemSelect::LABEL => $langNative,
                \FormItemSelect::ICON_CLASS => 'lang-flag ' . $langIso
            ];
        }
        return $options;
    }

    //=====================================================
    //== Validace =========================================
    //=====================================================
}