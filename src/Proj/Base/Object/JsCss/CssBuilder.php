<?php

namespace Proj\Base\Object\JsCss;

/**
 *
 */
class CssBuilder extends \CssBuilderAbstract {

    //=====================================================
    //== Konstanty ========================================
    //=====================================================

    //=====================================================
    //== Atributy =========================================
    //=====================================================

    private $applicationPath = null;

    //=====================================================
    //== Konstruktor ======================================
    //=====================================================

    /**
     *
     */
    public function __construct() {
        $this->cssList = new \ObjectList();
        $this->replaceCallback = function($path) {
            return str_replace('/lib/nil/', '/src/Nil/Resources/public/', $path);
        };
    }

    /**
     *
     */
    function init() {
        $this->add(\Bootstrap::CSS);
        $this->add(\Bootstrap::CSS_THEME);
        $this->add(\JQuery::CSS_UI_REDMOND);
        $this->addComponent(\Dialog::OBJECT);
        $this->addComponent(\Notificator::OBJECT);
        $this->addComponent(\Tooltip::OBJECT);
        $this->addComponent(\Form::OBJECT);
        $this->addComponent(\Grid::OBJECT);
        $this->addComponent(\Tabs::OBJECT);
        $this->addComponent(\Wizard::OBJECT);
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
    function getEmailArr() { return []; }         // HERE ENTER ADMIN EMAIL

    /**
     * @return string
     */
    function getEmailSubject() { return 'CSS BUILDER'; }

    /**
     * @return string
     */
    function getCssCachePath() { return ''; }

    /**
     * @return string
     */
    function getTagStyleCssString() { return ''; }

    /**
     * @param bool $echo
     * @return array
     */
    public function render($echo = true) {
        $paths = [];
        foreach ($this->getCssList() as $cssObject) {
            if ($cssObject instanceof \Css) {
                $paths[] = $cssObject->getPath();
            }
        }
        return $paths;
    }

    /**
     * @return string
     */
    function getApplicationPath() { return $this->applicationPath; }

    /**
     * @param string $v
     *
     * @return $this
     */
    public function setApplicationPath($v) { $this->applicationPath = $v;return $this; }

    /**
     * @return \CssBuilderAbstract
     */
    public function disableCache() {
//                var_dump('Je zakázána cache pro CSS !!! Než dáte push je třeba cache povolit !!! Napoveda: CssBuilder.php -> init()');
        $this->enableCache = false;
        return $this;
    }
}