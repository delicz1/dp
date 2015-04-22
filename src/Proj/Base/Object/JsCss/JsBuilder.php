<?php

namespace Proj\Base\Object\JsCss;

/**
 * @author necas
 */
class JsBuilder extends \JsBuilderAbstract {

    //=====================================================
    //== Atributy =========================================
    //=====================================================

    private $applicationPath = null;

    //=====================================================
    //== Konstruktor ======================================
    //=====================================================

    /**
     */
    public function __construct() {
        $this->jsList = new \ObjectList();
        $this->replaceCallback = function($path) {
            return str_replace('/lib/nil/', '/src/Nil/Resources/public/', $path);
        };
    }

    //=====================================================
    //== Verejne metody ===================================
    //=====================================================

    /**
     *
     */
    function init() {
        $this->add(\JQuery::JS);
        $this->add(\Bootstrap::JS);
        $this->add(\JQuery::CONFIG);
        $this->add(\JQuery::UI);
        $this->add(\JQuery::INHERIT);
        $this->add(\JQuery::TIMER);
        $this->addComponent(\Dialog::OBJECT);
        $this->addComponent(\Notificator::OBJECT);
        $this->addComponent(\Tooltip::OBJECT);
        $this->addComponent(\Form::OBJECT);
        $this->addComponent(\Grid::OBJECT);
        $this->addComponent(\Tabs::OBJECT);
        $this->addComponent(\Wizard::OBJECT);
        $this->addComponent(\TimelineAbstract::OBJECT);
        $this->addComponent(\Chart::OBJECT);
//        $this->addComponent(\Schedule::OBJECT);

        $this->disableCache();
    }

    /**
     *
     */
    function other() {}

    /**
     * @return array
     */
    function getEmailArr() { return []; } // HERE ENTER ADMIN EMAIL

    /**
     * @return string
     */
    function getEmailSubject() { return 'JS BUILDER'; }

    /**
     * @return string
     */
    function getJsCachePath() { return ''; }

    /**
     * @return string
     */
    function getTagScriptJsString() { return ''; }

    /**
     * @param bool $echo
     * @return array
     */
    public function render($echo = true) {
        $paths = [];
        foreach ($this->getJsList() as $jsObject) {
            if ($jsObject instanceof \Js) {
                $paths[] = $jsObject->getPath();
            }
        }
        return $paths;
    }

    /**
     * @return string
     */
    function getApplicationPath() { return $this->applicationPath; }

    /**
     * @return $this
     */
    public function disableCache() {
//        var_dump('Je zakázána cache pro JS !!! Než dáte push je třeba cache povolit !!! Napoveda: JsBuilder.php -> init()');
        $this->enableCache = false;
        return $this;
    }

    //=====================================================
    //== Verejne staticke metody ==========================
    //=====================================================

    /**
     * @param string $v
     *
     * @return $this
     */
    public function setApplicationPath($v) { $this->applicationPath = $v;return $this; }
}