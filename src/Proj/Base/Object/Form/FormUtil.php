<?php
/**
 * Created by PhpStorm.
 * User: kozakj
 * Date: 18.6.14
 * Time: 15:14
 */

namespace Proj\Base\Object\Form;
use Form;
use Proj\Base\Entity\User;

/**
 * Class FormUtil
 * @package Proj\Base\Object\Form
 */
class FormUtil {

    const INPUT_VIRTUAL_OBJECT_IDS = 'vir_ids';
    const INPUT_DELETED_OBJECT_IDS = 'del_ids';

    /**
     * @param DoctrineForm $form
     * @param $emptyObject - obecne jakykoliv objekt ktery ma metodu setId();
     * @param [] $dataList
     * @param string $btnAddInputName - nazev buttonu, ktery slouzi pro pridani záznamu
     * @param string $btnDeleteInputName - nazev buttonu, ktery slouzi pro odebrani zaznamu
     * @param string $objectIdInput - nazev parametru, ktery obsahuje id smazaneho zaznamu
     * @return array
     */
    public static function handleMultiItem(DoctrineForm $form, $objectName, $dataList, $btnAddInputName, $btnDeleteInputName, $objectIdInput) {
//        /** @var DbObject[]|ObjectList|Multiresult $dataList */
        $request = $form->getRequest();
//        if($dataList instanceof MultiResult || $dataList instanceof ObjectList) {
//            $dataList = $dataList->toArray();
//        }

        $form->addHidden(self::INPUT_VIRTUAL_OBJECT_IDS, null);
        $form->addHidden(self::INPUT_DELETED_OBJECT_IDS, null);

        $objectId = $request->getParam($objectIdInput);

        $virtualObjectId = $request->getParam(self::INPUT_VIRTUAL_OBJECT_IDS);
        $virtualObjectId = ($virtualObjectId !== null && $virtualObjectId !== '') ? explode(';',$virtualObjectId) : [];

        $idsToDelete = $request->getParam(self::INPUT_DELETED_OBJECT_IDS);
        $idsToDelete = ($idsToDelete !== null && $idsToDelete !== '') ? explode(';',$idsToDelete) : [];

        $isAdd = $request->getParam($btnAddInputName);
        $isDelete = $request->getParam($btnDeleteInputName);

        if($isDelete) {
            $idsToDelete[] = $objectId;
            $form[self::INPUT_DELETED_OBJECT_IDS]->setValue(implode(';', $idsToDelete));
        }

        if($isAdd) {
            if(!isset($virtualObjectId[0])) {
                $nextId = 0;
            }else {
                $nextId = end($virtualObjectId) + 1;
            }
            $virtualObjectId[] = $nextId;
            $form[self::INPUT_VIRTUAL_OBJECT_IDS]->setValue(implode(';', $virtualObjectId));
        }

        foreach($virtualObjectId as $id) {
            /** @noinspection PhpParamsInspection */
            $object = $form->getDoctrine()->getRepository('ProjBaseBundle:User')->find($id);
            if (!$object) {
                $object = new User();
            }
            $dataList[] = $object;
        }

        foreach($dataList as $key => $value) {
            $id = $value->getId();
            if(in_array($id, $idsToDelete)) {
                unset($dataList[$key]);
            }
        }

        return $dataList;
    }

    /**
     * @param Form $form
     * @param string $prefix
     * @return array
     */
    public static function getIdsOfDeletedObjects(Form $form, $prefix = '__fu_') {
        $persistentId = [];
        $ids = $form->getRequest()->getParam($prefix.self::INPUT_DELETED_OBJECT_IDS);
        $ids = ($ids !== null && $ids !== '') ? explode(';',$ids) : [];
        foreach($ids as $id) {
            if(strpos($id, $prefix) === false) {
                $persistentId[] = $id;
            }
        }
        return $persistentId;
    }
}