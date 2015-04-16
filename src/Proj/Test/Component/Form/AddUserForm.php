<?php
/**
 * Pridani uzivatele
 */

namespace Proj\Test\Component\Form;
use Doctrine\Bundle\DoctrineBundle\Registry;
use nil\Html;
use Notificator;
use Proj\Base\Entity\User;
use Proj\Base\Object\Form\DoctrineForm;
use Proj\Base\Object\Form\FormId;
use Proj\Base\Object\Locale\Formatter;
use Proj\Test\Component\Dialog\TestDialog;

/**
 * @author springer
 */
class AddUserForm extends DoctrineForm {

    //=====================================================
    //== Konstanty ========================================
    //=====================================================

    const ID = FormId::ADD_USER;
    const ACTION = '/testForm';
    const NAME = 'addUserForm';

    const INPUT_NAME = 'name';
    const INPUT_EMAIL = 'email';
    const INPUT_ROLE = 'role';
    const INPUT_PASSWORD = 'password';
    const INPUT_SURNAME = 'surname';

    const SUBMIT = 'save';

    //=====================================================
    //== Konfigurace ======================================
    //=====================================================

    /**
     * @param Formatter $formatter
     * @param \Request $request
     * @param Registry $doctrine
     * @return AddUserForm
     */
    public static function create(Formatter $formatter, \Request $request = null, Registry $doctrine = null) {
        $form = new self(self::NAME, self::ACTION, self::POST);
        $form->setFormater($formatter);
        $form->addSubmit(self::SUBMIT, 'form.save', 'glyphicon glyphicon-floppy-disk');
        if ($request instanceof \Request) {
            $form->setRequest($request);
            $form->doctrine = $doctrine;
            $form->setHelpManager(false);
            $form->init();
        }
        return $form;
    }

    protected function init() {

        $this->addText(self::INPUT_NAME, 'Jméno');
        $this->addText(self::INPUT_SURNAME, 'Příjmení');
        $this->addText(self::INPUT_EMAIL, 'E-mail');
        $this->addPassword(self::INPUT_PASSWORD, 'Heslo');
//        $this->addMultiSelect(self::INPUT_ROLE, 'Role', Role::USER, $this->roleOptions());

        $this->handle();
    }

    public function onSuccess() {
        $user = new User();
        $user->setEmail($this[self::INPUT_EMAIL]->getValue());
        $user->setName($this[self::INPUT_NAME]->getValue());
        $user->setSurname($this[self::INPUT_SURNAME]->getValue());
        $user->setPasswd($this[self::INPUT_PASSWORD]->getValue());

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        Notificator::add('SUCCESS', '', Notificator::TYPE_INFO);
        echo Html::el('script')->setHtml(TestDialog::close(TestDialog::DIV));
    }

    public function onError() {
        Notificator::add('ERROR', '', Notificator::TYPE_ERROR);
    }

//    private function roleOptions() {
//        $em = $this->getDoctrine()->getManager();
//        Role::
//    }

    //=====================================================
    //== Options ==========================================
    //=====================================================

    //=====================================================
    //== Validace =========================================
    //=====================================================
}