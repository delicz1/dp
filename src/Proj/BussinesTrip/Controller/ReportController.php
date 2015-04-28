<?php
/**
 * @author springer
 */

namespace Proj\BussinesTrip\Controller;

use Proj\BussinesTrip\Component\Form\TravelOrderForm;
use Proj\BussinesTrip\Entity\Trip;
use Proj\BussinesTrip\Report\TravelOrderReport;
use /** @noinspection PhpUnusedAliasInspection */
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use /** @noinspection PhpUnusedAliasInspection */
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Proj\Base\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("report", name="_report")
 * @Template()
 */
class ReportController extends BaseController {

    const TRIP_DETAIL      = '/report/detail';
    const TRIP_DETAIL_DATA = '/report/detailData';

    const TRAVEL_ORDER_FORM = '/report/travelOrderForm';
    const TRAVEL_ORDER_PRINT = '/report/travelOrderPrint';




    /**
     * @Route("/travelOrder")
     * @Template()
     */
    public function travelOrderAction() {

        $form = TravelOrderForm::create($this->getFormater(), $this->getRequestNil(), $this->getDoctrine());
        return ['form' => $form];
    }

    /**
     * @Route("/travelOrderForm")
     * @Template()
     */
    public function travelOrderFormAction() {

        $form = TravelOrderForm::create($this->getFormater(), $this->getRequestNil(), $this->getDoctrine());
        return ['form' => $form];
    }

    /**
     * @Route("/travelOrderPrint")
     * @Template()
     */
    public function travelOrderPrintAction() {

        $traverOrder = new TravelOrderReport($this->getSelfUser(), $this->getRequestNil(), $this->getDoctrine());
        return ['travelOrder' => $traverOrder];
    }

    /**
     * @Route("/tripDetail")
     * @Template()
     */
    public function tripDetailAction() {
        $id = $this->getRequestNil()->getParam(Trip::COLUMN_ID);
        $trip = $this->getDoctrine()->getRepository('ProjBussinesTripBundle:Trip')->find($id);
        return [
            'trip'         => $trip,
            'formatter'    => $this->getFormater(),
        ];
    }


    /**
     * @Route("/tripDetailPdf")
     */
    public function tripDetailPdfAction() {

        $id = $this->getRequestNil()->getParam(Trip::COLUMN_ID);
        $trip = $this->getDoctrine()->getRepository('ProjBussinesTripBundle:Trip')->find($id);
//
//        $request = $this->getRequestNil();
//        $content =  urldecode($request->getParam('content'));
//        $title = urldecode($request->getParam('title'));
//        $fileName = urldecode($request->getParam('fileName'));
//        $showFooter = urldecode($request->getParam('showFooter'));
//        $showHeader = urldecode($request->getParam('showHeader'));
//        $orientation = urldecode($request->getParam('orientation'));
//        $name = urldecode($request->getParam('name'));



//        $wkHtmlToPdfOption = ['orientation' => $orientation];
//        if ($showFooter) {
//            $wkHtmlToPdfOption['footer-html'] = $this->renderView('@RdrExport/ExportBasicMonthSum/exportFooter.html.twig', []);
//            $wkHtmlToPdfOption['footer-spacing'] = '10.5';
//        }
//
//        if ($showHeader) {
//            $dateTime = $formater->timestamp(\DateUtil::gmtToTimezone(time(), $timezone ), Formatter::FORMAT_DATE_TIME_SECOND );
//            $wkHtmlToPdfOption['header-html'] = $this->renderView('@RdrExport/ExportBasicMonthSum/exportHeader.html.twig', ['title' => $title, 'dateTime' =>$dateTime]);
//            $wkHtmlToPdfOption['header-spacing'] = '7';
//        }

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($this->renderView(
                'ProjBussinesTripBundle:Report:tripDetail.html.twig',
                ['trip' => $trip, 'formatter'    => $this->getFormater()])),
            200,
            [
                'Pragma'                => 'public',
                'Cache-Control'         => 'must-revalidate, post-check=0, pre-check=0',
                'Content-Type'          => 'application/pdf ;  charset=UTF-8',
                'Content-Disposition'   => 'attachment; filename="tripDetail-' . date("d-m-y"). '.pdf"',
                'Content-Transfer-Encoding'   => 'binary',
            ]
        );

    }
}