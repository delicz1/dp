<?php
/**
 * Pridani uzivatele
 */

namespace Proj\BussinesTrip\Component\Form;
use Doctrine\Bundle\DoctrineBundle\Registry;
use nil\Html;
use Notificator;
use Proj\Base\Entity\User;
use Proj\Base\Object\Form\DoctrineForm;
use Proj\Base\Object\Form\FormId;
use Proj\Base\Object\Locale\Formatter;
use Proj\BussinesTrip\Component\Dialog\EditUserDialog;
use Proj\BussinesTrip\Component\Grid\UserGrid;
use Proj\BussinesTrip\Controller\UserController;

/**
 * @author springer
 */
class EditUserForm extends DoctrineForm {

    //=====================================================
    //== Konstanty ========================================
    //=====================================================

    const ID = FormId::EDIT_USER;
    const ACTION = UserController::EDIT_FORM;
    const NAME = 'EditUserForm';

    const INPUT_NAME = 'name';
    const INPUT_EMAIL = 'email';
    const INPUT_ROLE = 'role';
    const INPUT_PASSWORD = 'password';
    const INPUT_SURNAME = 'surname';

    const SUBMIT = 'save';

    /** @var  User */
    public $user;

    //=====================================================
    //== Konfigurace ======================================
    //=====================================================

    /**
     * @param Formatter $formatter
     * @param \Request  $request
     * @param Registry  $doctrine
     * @param null      $user
     * @return EditUserForm
     */
    public static function create(Formatter $formatter, \Request $request = null, Registry $doctrine = null, $user = null) {
        $form = new self(self::NAME, self::ACTION, self::POST);
        $form->setFormater($formatter);
        $form->addSubmit(self::SUBMIT, 'form.save', 'glyphicon glyphicon-floppy-disk');
        if ($request instanceof \Request) {
            $form->setRequest($request);
            $form->doctrine = $doctrine;
            $form->user = $user;
            $form->setHelpManager(false);
            $form->init();
        }
        return $form;
    }

    protected function init() {

        $tr = $this->formater->getLangTranslator();

        $this->addText(self::INPUT_NAME, $tr->get('user.name'), $this->user->getName());
        $this->addText(self::INPUT_SURNAME, $tr->get('user.surname'), $this->user->getSurname());
        $this->addText(self::INPUT_EMAIL, $tr->get('user.email'), $this->user->getEmail())->addRuleEmail('');
        $this->addPassword(self::INPUT_PASSWORD, $tr->get('user.password'))->addRuleMethod('user.valid.password', 'rulePassword');
        $this->addSelect(User::COLUMN_STATUS, $tr->get('user.status'), $this->user->getStatus(), User:: $statusList);
        $this->addHidden('id', $this->user->getId());

        $this->handle();
    }

    public function onSuccess() {

        $user = $this->user;

        $user->setEmail($this[self::INPUT_EMAIL]->getValue());
        $user->setName($this[self::INPUT_NAME]->getValue());
        $user->setSurname($this[self::INPUT_SURNAME]->getValue());
        if ($this[self::INPUT_PASSWORD]->getValue()) {
            $user->setPasswd(sha1($this[self::INPUT_PASSWORD]->getValue()));
        }
        $user->setStatus($this[User::COLUMN_STATUS]->getValue());

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        Notificator::add('SUCCESS', '', Notificator::TYPE_INFO);
        $js = EditUserDialog::close(EditUserDialog::DIV);
        $js .= UserGrid::reload(UserGrid::ID);
        echo Html::el('script')->setHtml($js);
    }

    public function onError() {
        Notificator::add('ERROR', '', Notificator::TYPE_ERROR);
    }


    //=====================================================
    //== Options ==========================================
    //=====================================================

    //=====================================================
    //== Validace =========================================
    //=====================================================

    /**
     * @return bool
     */
    public function rulePassword() {
        $result = true;
        $value = $this[self::INPUT_PASSWORD]->getValue();
        if ($value) {
            $result = (mb_strlen($value) > 5);
        }
        return $result;
    }
}