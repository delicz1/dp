<?php
/**
 * @author necas
 */

namespace Proj\Base\Object\Form;

use Doctrine\Bundle\DoctrineBundle\Registry;

/**
 * Class ProjForm
 *
 * @package Proj\Base\Object\Form
 */
class DoctrineForm extends \Form {

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

    /**
     * @return string
     */
    public function __toString() {
        $html = '';
        try {
            $html = parent::__toString();
        } catch (\Exception $e) {
            dump($e);
        }
        return $html;
    }
}