<?php
/**
 * @author necas
 */

use Doctrine\ORM\EntityRepository;
use Proj\Base\Entity\FormHelp;
use Proj\Base\Object\Form\DoctrineForm;
use Proj\Base\Object\Form\DoctrineFormTabs;
use Proj\Base\Object\Form\FormHelpAddDialog;

/**
 * Class FormHelpUtil
 *
 * @package Nil\Object\Form
 */
class FormHelpUtil {

    /**
     * @param \FormItem $item
     *
     * @return null|FormHelp
     */
    public static function getHelp(\FormItem $item) {
        /** @var DoctrineForm|DoctrineFormTabs $form */
        $form = $item->getForm();
        $locale = $form->getFormater()->getLangTranslator()->getSystemLang()->getIso();
        $doctrine = $form->getDoctrine();
        /** @var EntityRepository $repo */
        $repo = $doctrine->getRepository('ProjBaseBundle:FormHelp');
        $help = $repo->findOneBy([
            FormHelp::COLUMN_FORM_ID => $form->getId(),
            FormHelp::COLUMN_LOCALE => $locale,
            FormHelp::COLUMN_INPUT_NAME => $item->getName()
        ]);
        return $help;
    }

    /**
     * @param FormItem $item
     *
     * @return Dialog
     */
    public static function getAddDialog(FormItem $item) {
        return FormHelpAddDialog::create($item);
    }

}