<?php
/**
 *
 */

namespace Proj\Base\Object\Locale;
use LangTranslatorInterface;

/**
 * Class SystemLang
 *
 * @package Proj\Base\Object\Locale
 */
class SystemLang implements \SystemLangInterface {

    //=====================================================
    //== Konstanty ========================================
    //=====================================================

    const CS = 'cs';
    const EN_US = 'en-US';

    const CS_NATIVE = 'Čeština';
    const EN_US_NATIVE = 'English US';

    const NATIVE = 'native';

    //=====================================================
    //== Staticke ciselniky ===============================
    //=====================================================

    private static $langArr = [
        self::CS => [
            self::NATIVE => self::CS_NATIVE
        ],
        self::EN_US => [
            self::NATIVE => self::EN_US_NATIVE
        ]
    ];

    //=====================================================
    //== Atributy =========================================
    //=====================================================

    private $iso;

    //=====================================================
    //== Verejne staticke metody ==========================
    //=====================================================

    /**
     * @return array
     */
    public static function getLangArr() {
        return self::$langArr;
    }

    //=====================================================
    //== Verejne metody ===================================
    //=====================================================

    /** @return LangTranslatorInterface */
    public function getTranslator() {
        return LangTranslator::getInstance($this);
    }

    /**
     * @param $v
     *
     * @return $this
     */
    public function setIso($v) {
        $this->iso = $v;
        return $this;
    }

    /** @return int */
    public function getId() {
        // TODO: Implement getId() method.
    }

    /** @return string */
    public function getIso() {
        return $this->iso;
    }

    /** @return string */
    public function getName() {
        // TODO: Implement getName() method.
    }

    /** @return string */
    public function getNative() {
        // TODO: Implement getNative() method.
    }

    /** @return string */
    public function getCollate() {
        // TODO: Implement getCollate() method.
    }

    /** @return int */
    public function getOrder() {
        // TODO: Implement getOrder() method.
    }

    /** @return int */
    public function getActive() {
        // TODO: Implement getActive() method.
    }
}