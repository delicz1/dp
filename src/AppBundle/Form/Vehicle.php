<?php
/**
 * Created by PhpStorm.
 * User: springer
 * Date: 30.1.15
 * Time: 21:17
 */

namespace AppBundle\Form;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class Vehicle extends \AppBundle\Entity\Vehicle {


    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('name', new NotBlank());
        $metadata->addPropertyConstraint('numberPlate', new NotBlank());
    }
}