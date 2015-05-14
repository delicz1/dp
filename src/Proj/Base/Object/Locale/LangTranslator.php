<?php

namespace Proj\Base\Object\Locale;

use LangTranslatorInterface;
use Symfony\Component\Translation\Translator;
use SystemLangInterface;

/**
 *
 */
class LangTranslator implements \LangTranslatorInterface {

    /**
     * @var \Symfony\Component\Translation\Translator
     */
    protected $translator;

    /**
     * @param SystemLangInterface $systemLang
     *
     * @return LangTranslatorInterface
     */
    public static function getInstance(SystemLangInterface $systemLang) {
        $instance = new LangTranslator();
        $instance->translator = new Translator($systemLang->getIso());
        return $instance;
    }

    /**
     * @param SystemLangInterface $systemLang
     * @param                     $key
     *
     * @return LangTranslatorInterface
     */
    public static function getBySystemLang(SystemLangInterface $systemLang, $key) {
        return (new Translator($systemLang->getIso()))->trans($key);
    }

    /**
     * Vrati modul pro dany jazyk.
     *
     * @param \SystemLangInterface $systemLang
     * @param   string             $modul
     *
     * @return  array
     */
    public static function getModulBySystemLang(SystemLangInterface $systemLang, $modul) {}

    /**
     * Nahradi promene v prekladu hodnotami z pole.
     *
     * @param   string $text
     * @param   array  $paramArray
     *
     * @return mixed|string
     */
    public static function replaceParam($text, $paramArray) {
        $size = count($paramArray);
        for($i = 1; $i <= $size; $i++) {
            $text = str_replace('$' . $i, $paramArray[$i-1], $text);
        }
        $text = preg_replace('/\$[0-9]*/', '', $text);          // vymaze promenne nenadefinovane v paramArray.
        return $text;
    }

    /**
     * Zapne / vypne prekladatelsky mod
     *
     * @author   springer
     * @internal param bool $transMod
     */
    public static function setTranslationMod() {
        // TODO: Implement setTranslationMod() method.
    }

    /**
     * promaze cache s preklady
     */
    public static function cleanCache() {
        // TODO: Implement cleanCache() method.
    }

    /**
     * Metoda zjisti jestli dany klic existuje.
     *
     * @param string $key
     *
     * @return bool
     */
    public function isReg($key) {
        return true;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public function get($key) {
        return $this->translator->trans($key);
    }

    /**
     * @param string $key
     * @param array  $paramArray
     *
     * @return string
     */
    public function getAndReplace($key, $paramArray) {
        $result = $this->get($key);
        $result = self::replaceParam($result, $paramArray);
        return $result;
    }

    /**
     * Vrati prelozeny klic retezce do prislusneho jazyka.
     *
     * @param string $modul
     *
     * @return string
     */
    public function getModulDataList($modul) {
        // TODO: Implement getModulDataList() method.
    }

    /**
     * @return SystemLangInterface
     */
    public function getSystemLang() {
        return (new SystemLang())->setIso($this->translator->getLocale());
    }

    /**
     * @param $v
     *
     * @return $this
     */
    public function setTranslator($v) {
        $this->translator = $v;
        return $this;
    }
}