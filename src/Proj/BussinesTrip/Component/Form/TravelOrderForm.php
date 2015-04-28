<?php
/**
 * Editace dopravnich prostredku
 */

namespace Proj\BussinesTrip\Component\Form;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use nil\Html;
use Notificator;
use Proj\Base\Entity\User;
use Proj\Base\Object\Form\DoctrineForm;
use Proj\Base\Object\Form\FormId;
use Proj\Base\Object\Form\FormUtil;
use Proj\Base\Object\Locale\Formatter;
use Proj\BussinesTrip\Component\Dialog\EditTripDialog;
use Proj\BussinesTrip\Component\Grid\TripGrid;
use Proj\BussinesTrip\Controller\ReportController;
use Proj\BussinesTrip\Controller\TripController;
use Proj\BussinesTrip\Entity\Trip;
use Proj\BussinesTrip\Entity\TripUser;
use Proj\BussinesTrip\Entity\Vehicle;

/**
 * @author springer
 */
class TravelOrderForm extends DoctrineForm {

    //=====================================================
    //== Konstanty ========================================
    //=====================================================

    const ID     = FormId::TRAVEL_ORDER;
    const ACTION = ReportController::TRAVEL_ORDER_FORM;
    const NAME   = 'EditTripForm';
    const SELECT_USERS = 'selectedUsers';
    const INPUT_TIME_FROM = 'timeFrom';
    const INPUT_TIME_TO = 'timeTo';

    const SUBMIT = 'save';

    //=====================================================
    //== Konfigurace ======================================
    //=====================================================

    /**
     * @param Formatter $formatter
     * @param \Request  $request
     * @param Registry  $doctrine
     * @return self
     */
    public static function create(Formatter $formatter, \Request $request = null, Registry $doctrine = null) {
        $form = new self(self::NAME, self::ACTION, self::POST);
        $form->setFormater($formatter);
        $form->addSubmit(self::SUBMIT, 'form.save', 'glyphicon glyphicon-floppy-disk');
        if ($request instanceof \Request) {
            $form->setRequest($request);
            $form->doctrine = $doctrine;
            $form->setHelpManager(false);
            $form->init();
        }
        return $form;
    }

    protected function init() {

        $timeFrom = \DateUtil::getStartDay();
        $timeTo = \DateUtil::getEndDay();


        $this->addSelect(self::SELECT_USERS, 'user.user', null, $this->getUserOptions());
        $this->addDate(self::INPUT_TIME_FROM, 'trip.time.from', $timeFrom, \FormItemDate::MODE_DATETIME);
        $this->addDate(self::INPUT_TIME_TO, 'trip.time.to', $timeTo, \FormItemDate::MODE_DATETIME);

        $this->handle();
    }

    public function onSuccess() {


        Notificator::add('SUCCESS', '', Notificator::TYPE_INFO);

        $url = ReportController::TRAVEL_ORDER_PRINT;
        $url .= '?' . self::SELECT_USERS . '=' . $this[self::SELECT_USERS]->getValue();
        $url .= '&' . self::INPUT_TIME_FROM . '=' . $this[self::INPUT_TIME_FROM]->getValue();
        $url .= '&' . self::INPUT_TIME_TO . '=' . $this[self::INPUT_TIME_TO]->getValue();
        $js = \AjaxUpdater::create('reportData', $url)->render(false, false);
        echo Html::el('script')->setHtml($js);
    }

    public function onError() {
        Notificator::add('ERROR', '', Notificator::TYPE_ERROR);
    }



    //=====================================================
    //== Options ==========================================
    //=====================================================

    /**
     * @return array
     */
    private function getUserOptions() {
        $list = [];
        /** @var EntityRepository $repository */
        $repository = $this->doctrine->getRepository('ProjBaseBundle:User');
        /** @var QueryBuilder $qb */
        $qb = $repository->createQueryBuilder('u');
        $qb->where('u.status = ' . User::STATUS_ACTIVE);

        /** @var User[] $result */
        $result = $qb->getQuery()->getResult();
        foreach ($result as $user) {
            $list[$user->getId()] = $user->getFullName();
        }
        return $list;


    }

    //=====================================================
    //== Validace =========================================
    //=====================================================
}