<?php
/**
 * Created by PhpStorm.
 * User: jedlicka
 * Date: 30.9.14
 * Time: 11:31
 */

/**
 * Class GridDoctrineAbstract
 */
abstract class GridDoctrineAbstract extends \GridAbstract {

    /**
     * @param \Doctrine\Bundle\DoctrineBundle\Registry $doctrine
     * @param                                          $request
     * @param null                                     $paramList
     * @param null                                     $templatePath
     * @param string                                   $typeRender
     * @param null                                     $columnGridManager
     */
    public static function renderDataDoctrine(Doctrine\Bundle\DoctrineBundle\Registry $doctrine, $request, $paramList = null, $templatePath = null, $typeRender = self::RENDERER_GRID, $columnGridManager = null) {
        $paramList = ($paramList instanceof \GenericClass) ? $paramList : new \GenericClass();
        $gridFilter = \GridFilterDoctrine::create($request);
        $paramList->_grid->filter = $gridFilter;
        $paramList->_grid->doctrine = $doctrine;
        $paramList->_grid->id = $gridFilter->getGridId();
        $repository = static::getRepository($doctrine, $paramList, $gridFilter);
        $dataList = null;
        $pager = null;
        if (($repository !== null)) {
            $qb = static::getQueryBuilder($repository, $doctrine, $paramList, $gridFilter);
            $qb = $gridFilter->getQueryBuilder($qb);
            $paramList->_grid->queryBuilder = $qb;
            $pager = \GridPagerDoctrine::createQueryBuilder($doctrine, $repository, $qb, $gridFilter->getPage(), $gridFilter->getRows(), null, $request);
            $paramList->_grid->pager = $pager;
            $qb = $pager->getQueryBuilder();
            $dataList = $qb->getQuery()->getResult();
        }
        $gridDataRender = self::getDataRender(static::$dataType, $templatePath, $dataList, $paramList, $pager, $gridFilter, $typeRender);
        $gridDataRender->setGridClass(get_called_class());
        if ($templatePath === null) {
            self::setRenders($gridDataRender, $paramList);
        }
        echo $gridDataRender->render();
        exit;
    }

    /**
     * @param \Doctrine\Bundle\DoctrineBundle\Registry $doctrine
     * @param $paramList
     * @param $gridFilter
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public static function getRepository(\Doctrine\Bundle\DoctrineBundle\Registry $doctrine, $paramList, $gridFilter) {
        return null;
    }

    /**
     * @param \Doctrine\ORM\EntityRepository $repository
     * @param \Doctrine\Bundle\DoctrineBundle\Registry $doctrine
     * @param $paramList
     * @param $gridFilter
     * @return \Doctrine\ORM\QueryBuilder|null
     */
    public static function getQueryBuilder(\Doctrine\ORM\EntityRepository $repository, \Doctrine\Bundle\DoctrineBundle\Registry $doctrine, $paramList, $gridFilter) {
        return $repository->createQueryBuilder('x');
    }

}