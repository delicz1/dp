<?php
/**
 * @author springer
 */

namespace Proj\BussinesTrip\Report;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Proj\Base\Entity\User;
use Proj\BussinesTrip\Component\Form\TravelOrderForm;
use Proj\BussinesTrip\Entity\Trip;

class TravelOrderReport {

    private $fromPosition = '';
    private $toPosition = '';
    private $purpose = '';


    /**
     * @var User
     */
    private $user;
    /**
     * @var \Request
     */
    private $request;
    /**
     * @var Registry
     */
    private $doctrine;

    /**
     * @var User
     */
    private $tripUser;

    /**
     * @param User     $user
     * @param \Request $request
     * @param Registry $doctrine
     */
    public function __construct(User $user, \Request $request, Registry $doctrine) {

        $this->user = $user;
        $this->request = $request;
        $this->doctrine = $doctrine;
        $this->tripUser = $doctrine->getRepository('ProjBaseBundle:User')->find($this->request->get(TravelOrderForm::SELECT_USERS));
        $tripList = $this->getTripList();
        foreach ($tripList as $trip) {
            if ($this->fromPosition == '') {
                $this->fromPosition = $trip->getPointFrom();
                $this->purpose = $trip->getPurpose();
            }
            $this->toPosition = $trip->getPointTo();
        }
    }


    /**
     * @return Trip[]
     */
    private function getTripList() {
        $userId = $this->request->get(TravelOrderForm::SELECT_USERS);
        $timeFrom = $this->request->get(TravelOrderForm::INPUT_TIME_FROM);
        $timeTo = $this->request->get(TravelOrderForm::INPUT_TIME_TO);
        /** @var EntityRepository $repository */
//        dump($this->request); exit;
        $repository = $this->doctrine->getRepository('ProjBussinesTripBundle:Trip');
        /** @var QueryBuilder $qb */
        $qb = $repository->createQueryBuilder('t');
        $qb->join('t.tripUsers', 'tu');
        $qb->join('tu.user', 'u');
        $qb->where('u.id =' . $userId);
        $qb->andWhere(' t.' . Trip::COLUMN_TIME_FROM . ' >= ' . (int)$timeFrom);
        $qb->andWhere('t.' . Trip::COLUMN_TIME_FROM . ' <= ' . (int)$timeTo);

        return $qb->getQuery()->getResult();
    }

    /**
     * @return string
     */
    public function getFromPosition() {
        return $this->fromPosition;
    }

    /**
     * @return string
     */
    public function getPurpose() {
        return $this->purpose;
    }

    /**
     * @return string
     */
    public function getToPosition() {
        return $this->toPosition;
    }

    /**
     * @return User
     */
    public function getTripUser() {
        return $this->tripUser;
    }
}