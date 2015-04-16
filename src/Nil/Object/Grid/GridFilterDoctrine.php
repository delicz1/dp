<?php
/**
 * Class GridFilterDoctrine
 * @author jedlicka
 */
class GridFilterDoctrine extends \GridFilter {

    /**
     * Vrati instanci objektu GridFilter.
     * @param $request
     * @return GridFilterDoctrine
     * @author jedlicka
     */
    public static function create($request = null) {
        return new GridFilterDoctrine($request);
    }

    //=====================================================
    //== Konstruktor ======================================
    //=====================================================

    /**
     * @param null $request
     */
    public function __construct($request = null) {
        parent::__construct($request);
    }

    //=====================================================
    //== Verejne metody ===================================
    //=====================================================

    public function getQueryBuilder(\Doctrine\ORM\QueryBuilder $qb) {
        $orderArr = $this->_getOrder();
        if (count($orderArr) > 0) {
            foreach ($orderArr as $column => $order) {
                $qb->addOrderBy($column, $order);
            }
        }
        $this->applyFiltersQueryBuilder($qb);
        return $qb;
    }

    /**
     * Sestavi cast SQL dotazu pro razeni.
     * @return array
     */
    public function _getOrder() {
        $order = [];
        if (($this->getSidx() !== null && $this->getSidx() != '') && ($this->getSord() !== null)) {
            if (is_array($this->getSidx())) {
                $orderArr = [];
                $sord = strtoupper($this->getSord());
                /** @var array $sidxF */
                $sidxF = $this->getSidx();
                foreach ($sidxF as $index => $sidx) {
                    $orderRule = $sord;
                    if (is_array($sord) && isset($sord[$index])) {
                        $orderRule = $sord[$index];
                    }
                    $orderArr[] = [$sidx => $orderRule];
                }
                $order = $orderArr;
            } else {
                $order = [$this->getSidx() => strtoupper($this->getSord())];
            }
        }
        return $order;
    }

    //=====================================================
    //== Privatni metody ==================================
    //=====================================================

    /**
     * @param \Doctrine\ORM\QueryBuilder $qb
     */
    private function applyFiltersQueryBuilder(\Doctrine\ORM\QueryBuilder $qb) {
        if ($this->getSearch() == 'true') {
            $filterWhere = $this->getFilterWhere();
            $filters = $this->getFilters();
            $filterObj = json_decode($filters);
            $groupOp = strtolower($filterObj->groupOp);
            if ($groupOp == 'or') {
                $qb->orWhere($filterWhere);
            } elseif ($groupOp == 'and') {
                $qb->andWhere($filterWhere);
            }
        }
    }

}
