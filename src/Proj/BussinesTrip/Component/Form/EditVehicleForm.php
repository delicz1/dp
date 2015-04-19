<?php
/**
 * Editace dopravnich prostredku
 */

namespace Proj\BussinesTrip\Component\Form;
use Doctrine\Bundle\DoctrineBundle\Registry;
use nil\Html;
use Notificator;
use Proj\Base\Object\Form\DoctrineForm;
use Proj\Base\Object\Form\FormId;
use Proj\Base\Object\Locale\Formatter;
use Proj\BussinesTrip\Component\Dialog\EditVehicleDialog;
use Proj\BussinesTrip\Component\Grid\VehicleGrid;
use Proj\BussinesTrip\Controller\VehicleController;
use Proj\BussinesTrip\Entity\Vehicle;

/**
 * @author springer
 */
class EditVehicleForm extends DoctrineForm {

    //=====================================================
    //== Konstanty ========================================
    //=====================================================

    const ID     = FormId::EDIT_USER;
    const ACTION = VehicleController::EDIT_FORM;
    const NAME   = 'EditVehicleForm';

    const INPUT_NAME     = 'name';
    const INPUT_CAPACITY = 'capacity';
    const INPUT_TYPE     = 'type';
    const INPUT_STATUS   = 'status';

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
     * @param \Request  $request
     * @param Registry  $doctrine
     * @param Vehicle   $vehicle
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
        $statusValue = $this->vehicle->getId() ? $this->vehicle->getStatus() : Vehicle::STATUS_ACTIVE;

        $this->addText(self::INPUT_NAME, $tr->get('vehicle.name'), $this->vehicle->getName());
        $this->addHidden(Vehicle::COLUMN_ID, $this->vehicle->getId());
        $this->addSelect(self::INPUT_TYPE, $tr->get('vehicle.type'), $this->vehicle->getType(), Vehicle::$typeList);
        $this->addText(self::INPUT_CAPACITY, $tr->get('vehicle.capacity'), $this->vehicle->getCapacity())->addRuleInteger()->addRuleRange('', 1, 9999);
        $this->addSwitch(self::INPUT_STATUS, $tr->get('vehicle.status'), $statusValue)
            ->setOption1(Vehicle::STATUS_ACTIVE, Vehicle::STATUS_ACTIVE_TRANS)
            ->setOption2(Vehicle::STATUS_DELETED, Vehicle::STATUS_DELETED_TRANS);
        $this->handle();
    }

    public function onSuccess() {

        $em = $this->getDoctrine()->getManager();
        $vehicle = $this->vehicle;

        $vehicle->setName($this[self::INPUT_NAME]->getValue());
        $vehicle->setType($this[self::INPUT_TYPE]->getValue());
        $vehicle->setCapacity($this[self::INPUT_CAPACITY]->getValue());
        $vehicle->setStatus($this[self::INPUT_STATUS]->getValue());

        $em->persist($vehicle);
        $em->flush();

        Notificator::add('SUCCESS', '', Notificator::TYPE_INFO);
        $js = EditVehicleDialog::close(EditVehicleDialog::DIV);
        $js .= VehicleGrid::reload(VehicleGrid::ID);
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
}