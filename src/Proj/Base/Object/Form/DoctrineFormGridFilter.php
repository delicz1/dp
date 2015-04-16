<?php
/**
 * @author necas
 */

namespace Proj\Base\Object\Form;

use Doctrine\Bundle\DoctrineBundle\Registry;

/**
 * Class ProjFormGridFilter
 *
 * @package Proj\Base\Object\Form
 */
class DoctrineFormGridFilter extends \FormGridFilter {

    /**
     * @var Registry
     */
    protected $doctrine;

    /**
     * @param Registry $doctrine
     *
     * @return $this
     */
    public function setDoctrine(Registry $doctrine) { $this->doctrine = $doctrine; return $this; }

    /**
     * @return Registry
     */
    public function getDoctrine() { return $this->doctrine; }
}