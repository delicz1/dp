<?php
/**
 *
 */

namespace Proj\Base\Twig;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Proj\Base\Entity\User;

/**
 * Class BaseExtension
 *
 * @package Proj\Base\Twig
 */
class BaseExtension extends \Twig_Extension {

    /**
     * @var User
     */
    protected $context;

    /**
     * @var Registry
     */
    protected $doctrine;

    /**
     * @return array
     */
    public function getFilters() {
        return [
            new \Twig_SimpleFilter('base64_encode', function($str) { return base64_encode($str); }),
            new \Twig_SimpleFilter('base64_decode', function($str) { return base64_encode($str); }),
            new \Twig_SimpleFilter('stream_get_contents', function($r) { return stream_get_contents($r); }),
        ];
    }

    /**
     * @return array
     */
    public function getTests() {
        return [
            'instanceof' =>  new \Twig_Function_Method($this, 'isInstanceof')
        ];
    }

    /**
     * @param $object
     * @param $class
     *
     * @return bool
     */
    public function isInstanceof($object, $class) {
        $reflectionClass = new \ReflectionClass($class);
        return $reflectionClass->isInstance($object);
    }

    /**
     * @return string
     */
    public function getName() {
        return 'base_extension';
    }

}