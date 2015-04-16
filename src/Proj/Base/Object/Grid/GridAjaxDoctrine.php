<?php
namespace Proj\Base\Object\Grid;

use LangTranslatorInterface;

/**
 * Class GridAjaxProj
 */
abstract class GridAjaxDoctrine extends \GridAjax {

    //=====================================================
    //== Konstruktor ======================================
    //=====================================================

    /**
     * @param null|string $url
     * @param null|string $id
     * @param null|string $pagerId
     */
    public function __construct($url = null, $id = null, $pagerId = null) {
        parent::__construct($url, $id, $pagerId);
        $this->option->shrinkToFit = true;
        $this->option->altRows = false;
        $this->autoresizewidth = true;
        $this->soption->pager->hideByRecords = true;
        $this->disableSelectRowOnClickRow();
    }

    /**
     * @param string $gridId
     * @param string $url
     * @param int $objectId
     * @param LangTranslatorInterface $translator
     * @param string $classButton
     * @param string $classSpan
     * @return \nil\Html
     */
    public static function getDeleteButton($gridId, $url, $objectId, $translator, $classButton = 'btn btn-danger', $classSpan = 'glyphicon glyphicon-16 glyphicon-trash') {
        $option = \Dialog::getDefaultOption();
        $message = $translator->get('form.confirm.delete');
        $option->dialog->title = $translator->get('form.delete');
        $option->dialog->width = 260;
        $option->dialog->height = 80;
        $option->dialog->image->cls = 'glyphicon glyphicon-32 glyphicon-trash';
        $option->dialog->css->padding = '5px';
        $option->ajax = null;
        $deleteTitle = $translator->get('form.delete');
        $cancelTitle = $translator->get('form.cancel');
        $deleteIcon = "glyphicon glyphicon-16 glyphicon-trash";
        $cancelIcon = "glyphicon glyphicon-16 glyphicon-remove";
        $url .= ((strpos($url, '?') === false) ? '?' : '&') . "gv[]=" . $objectId;
        $updater = \AjaxUpdater::create('', $url, self::reload($gridId));
        $delete = $updater->render(false, false);
        $action = $delete . \Dialog::CLOSE;
        $dialog = new \Dialog('', $message, $option);
        $dialog->addButton($action, $deleteTitle, $deleteIcon, 'btn-danger');
        $dialog->addButton(\Dialog::CUSTOM_CLOSE, $cancelTitle, $cancelIcon);
        $dialog->setCloseFunction(\Dialog::CLOSE);
        $onclick = $dialog->render(false, false);
        return self::_getButton($onclick, $classButton, $classSpan, $translator->get('form.delete'));
    }

}