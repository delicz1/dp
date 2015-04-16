<?php

/**
 * Class Grid
 * @author jedlicka
 */
class Grid extends GridDoctrineAbstract {

    //=========================================================================
    //== Konstanty ============================================================
    //=========================================================================

    const OBJECT = '\Grid';

    //=========================================================================
    //== Verejne metody =======================================================
    //=========================================================================

    /**
     * @return void
     * @author jedlicka
     */
    public function setDefaultOption() {
        $this->option->multiboxonly = true;
    }

    //=========================================================================
    //== Verejne staticke metody ==============================================
    //=========================================================================

    /**
     * @param \JsBuilderAbstract $jb
     */
    public static function includeJs(\JsBuilderAbstract $jb) {
        parent::includeJs($jb);
        $jb->add(self::JS_UTIL);
        $path = str_replace(['XXX'], ['cs'], self::JS_LOCALE);
        $jb->addNoCache($path);
    }

    /**
     * @param \CssBuilderAbstract $cb
     */
    public static function includeCss(\CssBuilderAbstract $cb) {
        parent::includeCss($cb);
    }
}