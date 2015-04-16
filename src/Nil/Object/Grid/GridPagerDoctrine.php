<?php
/**
 * Created by PhpStorm.
 * User: jedlicka
 * Date: 30.9.14
 * Time: 11:43
 */

use Doctrine\DBAL\Driver\PDOSqlsrv\Connection;

class GridPagerDoctrine extends \GridPager{

    /**
     * @var \Doctrine\ORM\QueryBuilder
     */
    private $qb = null;

    /**
     * @var Doctrine\Bundle\DoctrineBundle\Registry
     */
    private $doctrine;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    private $repository;

    /**
     * @var Request
     */
    private $request;

    public static function createQueryBuilder(Doctrine\Bundle\DoctrineBundle\Registry $doctrine, \Doctrine\ORM\EntityRepository $repository, \Doctrine\ORM\QueryBuilder $qb, $page = null, $limit = null, $filter = null, $request = null) {
        $instance = new GridPagerDoctrine(null, $page, $limit, $filter);
        $instance->qb = $qb;
        $instance->doctrine = $doctrine;
        $instance->request = $request;
        $instance->repository = $repository;
        $instance->cnt = $instance->getCnt();
        $instance->run();
        return $instance;
    }

    /**
     * Ziska pocet zaznamu.
     * @return int
     * @author jedlicka
     */
    protected function getCnt() {
        $count = 2000;
        $hak = $this->request->get('hak');
        if ($hak == '1') {
            $qb = $this->qb;
            if ($qb !== null) {
                $paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($qb);
                $count = count($paginator);
            }
        }
        return $count;
    }

    public function getQueryBuilder() {
        $this->qb
            ->setFirstResult($this->getStart())
            ->setMaxResults($this->_getLimit());
        return $this->qb;
    }

}