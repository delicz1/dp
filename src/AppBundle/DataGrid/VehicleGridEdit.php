<?php
/**
 * Created by PhpStorm.
 * User: springer
 * Date: 31.1.15
 * Time: 5:01
 */

namespace AppBundle\DataGrid;

use Doctrine\ORM\EntityNotFoundException;
use AppBundle\Entity\Vehicle;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Thrace\DataGridBundle\Event\RowEvent;

class VehicleGridEdit extends ContainerAware {

    public function onRowAdd(RowEvent $event)
    {
        if ($event->getDataGridName() !== VehicleGrid::IDENTIFIER){
            return;
        }

        $vehicle = new Vehicle();
        $vehicle->setName($this->container->get('request')->request->get('name'));
        $vehicle->setNumberPlate($this->container->get('request')->request->get('numberPlate'));
        $vehicle->setType($this->container->get('request')->request->get('type'));
        $vehicle->setCapacity($this->container->get('request')->request->get('capacity'));

        $this->process($vehicle, $event);
    }

    public function onRowEdit(RowEvent $event)
    {
        if ($event->getDataGridName() !== VehicleGrid::IDENTIFIER){
            return;
        }

        $repo = $this->container->get('doctrine.orm.entity_manager')->getRepository('AppBundle:Vehicle');
        /** @var Vehicle $vehicle */
        $vehicle = $repo->find($event->getId());

        if (!$vehicle){
            throw new EntityNotFoundException();
        }

        $vehicle->setName($this->container->get('request')->request->get('name'));
        $vehicle->setNumberPlate($this->container->get('request')->request->get('numberPlate'));
        $vehicle->setCapacity($this->container->get('request')->request->get('capacity'));
        $vehicle->setType($this->container->get('request')->request->get('type'));

        $this->process($vehicle, $event);
    }

    public function onRowDelete(RowEvent $event)
    {
        if ($event->getDataGridName() !== VehicleGrid::IDENTIFIER){
            return;
        }

        $repo = $this->container->get('doctrine.orm.entity_manager')->getRepository('AppBundle:Vehicle');

        $product = $repo->find($event->getId());

        if (!$product){
            throw new EntityNotFoundException();
        }

        $this->container->get('doctrine.orm.entity_manager')->remove($product);
        $this->container->get('doctrine.orm.entity_manager')->flush();

        $event->setSuccess(true);

    }

    protected function process(Vehicle $vehicle, RowEvent $event) {
        $errors = $this->container->get('validator')->validate($vehicle, array('default'));
        $errorData = $this->errorsToArray($errors);
        if ((int)$vehicle->getCapacity() < 1) {
            $errorData[] = "Špatna kapacita";
        }

        if (!empty($errorData)){
            $event->setErrors($errorData);
            $event->setSuccess(false);
        } else {
            $this->container->get('doctrine.orm.entity_manager')->persist($vehicle);
            $this->container->get('doctrine.orm.entity_manager')->flush();
            $event->setSuccess(true);
        }
    }

    /**
     * @param ConstraintViolationListInterface $errors
     * @return array
     */
    protected function errorsToArray($errors)
    {
        $data = array();
        foreach ($errors as $error) {
            $data[] = $error->getMessage();
        }
        return $data;
    }
}