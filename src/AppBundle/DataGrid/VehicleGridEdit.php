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
use AppBundle\DataGrid\VehicleGrid;
use Symfony\Component\Form\FormError;
use Thrace\DataGridBundle\Event\RowEvent;

class VehicleGridEdit extends ContainerAware {

    public function onRowAdd(RowEvent $event)
    {
        if ($event->getDataGridName() !== VehicleGrid::IDENTIFIER){
            return;
        }

        $vehicle = new Vehicle();
        $vehicle->setName($this->container->get('request')->request->get('name'));
        $vehicle->setNumberPlate($this->container->get('request')->request->get('numberPlace'));
        $vehicle->setType($this->container->get('request')->request->get('type'));
        $vehicle->setCapacity($this->container->get('request')->request->get('capacity'));

        $this->process($vehicle, $event);
    }

    public function onRowEdit(RowEvent $event)
    {
        if ($event->getDataGridName() !== ProductManagementBuilder::IDENTIFIER){
            return;
        }

        $repo = $this->container->get('doctrine.orm.entity_manager')->getRepository('AppBundle:Product');

        $product = $repo->findOneById($event->getId());

        if (!$product){
            throw new EntityNotFoundException();
        }

        $product->setName($this->container->get('request')->request->get('name'));
        $product->setPrice($this->container->get('request')->request->get('price'));

        $this->process($product, $event);

    }

    public function onRowDelete(RowEvent $event)
    {
        if ($event->getDataGridName() !== ProductManagementBuilder::IDENTIFIER){
            return;
        }

        $repo = $this->container->get('doctrine.orm.entity_manager')->getRepository('AppBundle:Product');

        $product = $repo->findOneById($event->getId());

        if (!$product){
            throw new EntityNotFoundException();
        }

        $this->container->get('doctrine.orm.entity_manager')->remove($product);
        $this->container->get('doctrine.orm.entity_manager')->flush();

        $event->setSuccess(true);

    }

    protected function process(Vehicle $vehicle, RowEvent $event)
    {
        $errors = $this->container->get('validator')->validate($vehicle, array('default'));

        if ($errors->count() > 0){
            $event->setErrors($this->errorsToArray($errors));
            $event->setSuccess(false);

        } else {
            $this->container->get('doctrine.orm.entity_manager')->persist($vehicle);
            $this->container->get('doctrine.orm.entity_manager')->flush();
            $event->setSuccess(true);
        }
    }

    /**
     * @param FormError[] $errors
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