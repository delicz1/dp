<?php

namespace Proj\BussinesTrip\Component\Form;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Proj\Base\Object\Form\DoctrineFormGridFilter;
use Proj\Base\Object\Form\FormId;
use Proj\Base\Object\Locale\Formatter;
use Proj\BussinesTrip\Entity\Trip;

/**
 * Class OverviewApproachFilterForm
 */
class TripGridFilterForm extends DoctrineFormGridFilter {

    //=====================================================
    //== Konstanty ========================================
    //=====================================================

    const ID = FormId::TRIP_GRID_FILTER;
    const ACTION = '';
    const NAME = 'tripGridFilter';

    //=====================================================
    //== Vnorene objekty ==================================
    //=====================================================


    //=====================================================
    //== Konfigurace ======================================
    //=====================================================

    /**
     * @param Formatter $formater
     * @param \Grid    $grid
     * @param Registry $doctrine
     * @param \Request $request
     * @return self
     */
    public static function create(Formatter $formater, \Grid $grid, Registry $doctrine, \Request $request = null) {
        $form = new self(self::NAME . $grid->getId(), self::ACTION, self::POST);
        $form->setFormater($formater);
        $form->doctrine = $doctrine;
        if ($request instanceof \Request) {
            $form->setRequest($request);
            $form->init();
        }
        return $form;
    }

    protected function init() {
//        $trans = $this->getFormater()->getLangTranslator();
        $startDay = \DateUtil::getStartDay(null, -1);
        $endDay = \DateUtil::getEndDay();
        $dr = $this->addDateRange(Trip::COLUMN_TIME_FROM, '', [$startDay, $endDay]);
        $this->addText('free_capacity', '', '')->setSOpt(\GridAbstract::SOPT_DO_NOT_JOIN_TO_QUERY);
//            ->setMinPrefix(lcfirst($trans->get('overview.form.from') . ':&nbsp;'));
//            ->setMaxPrefix('&nbsp;&nbsp;&nbsp;' . lcfirst($trans->get('overview.form.to')) . ':&nbsp;');
        $dr->setWidth(60);
        $this->handle();
    }

}