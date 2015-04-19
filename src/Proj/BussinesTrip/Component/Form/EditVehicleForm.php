<?php
/**
 * Editace dopravnich prostredku
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
use Proj\BussinesTrip\Controller\UserController;
use Proj\BussinesTrip\Controller\VehicleController;
use Proj\BussinesTrip\Entity\Vehicle;

/**
 * @author springer
 */
class EditVehicleForm extends DoctrineForm {

    //=====================================================
    //== Konstanty ========================================
    //=====================================================

    const ID = FormId::EDIT_USER;
    const ACTION = VehicleController::EDIT_FORM;
    const NAME = 'EditVehicleForm';

    const INPUT_NAME = 'name';
    const INPUT_CAPACITY = 'capacity';
    const INPUT_TYPE = 'type';

    const SUBMIT = 'save';

    //=====================================================
    //== Konfigurace ======================================
    //=====================================================

    /**
     * @var Vehicle
     */
    public $vehicle;

    /**
     * @param Formatter $formatter
     * @param \Request $request
     * @param Registry $doctrine
     * @return EditUserForm
     */
    public static function create(Formatter $formatter, \Request $request = null, Registry $doctrine = null, Vehicle $vehicle = null) {
        $form = new self(self::NAME, self::ACTION, self::POST);
        $form->setFormater($formatter);
        $form->addSubmit(self::SUBMIT, 'form.save', 'glyphicon glyphicon-floppy-disk');
        if ($request instanceof \Request) {
            $form->setRequest($request);
            $form->doctrine = $doctrine;
            $form->vehicle = $vehicle;
            $form->setHelpManager(false);
            $form->init();
        }
        return $form;
    }

    protected function init() {

        $tr = $this->formater->getLangTranslator();

        $this->addText(self::INPUT_NAME, $tr->get('vehicle.name'), $this->vehicle->getName());
        $this->addSelect(self::INPUT_TYPE, $tr->get('vehicle.type'), $this->vehicle->getType(), Vehicle::$typeList);
        $this->addText(self::INPUT_CAPACITY, $tr->get('vehicle.capacity'))->addRuleInteger()->addRuleRange('', 1, 9999);
        $this->handle();
    }

    public function onSuccess() {

        $this->vehicle->setName($this[self::INPUT_NAME]->getValue());
        $this->vehicle->setType($this[self::INPUT_TYPE]->getValue());
        $this->vehicle->setCapacity($this[self::INPUT_CAPACITY]->getValue());

        $em = $this->getDoctrine()->getManager();
        $em->persist($this->vehicle);
        $em->flush();
        Notificator::add('SUCCESS', '', Notificator::TYPE_INFO);
        echo Html::el('script')->setHtml(EditUserDialog::close(EditUserDialog::DIV));
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
}