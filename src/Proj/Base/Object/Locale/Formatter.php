<?php
/**
 * @author necas
 */
namespace Proj\Base\Object\Locale;

/**
 * Class Formatter
 *
 * @package Proj\Base\Object\Locale
 */
class Formatter implements \FormatterInterface {

    /**
     * @var LangTranslator
     */
    protected $langTranslator;

    /**
     * @param LangTranslator $langTranslator
     *
     * @return $this
     */
    public static function getInstance(LangTranslator $langTranslator) {
        return (new Formatter())->setLangTranslator($langTranslator);
    }

    /**
     * @param \LangTranslatorInterface $langTranslator
     *
     * @return $this
     */
    public function setLangTranslator(\LangTranslatorInterface $langTranslator) {
        $this->langTranslator = $langTranslator;
        return $this;
    }

    /**
     * @return LangTranslator
     */
    public function getLangTranslator() {
        return $this->langTranslator;
    }

    /**
     * @param int    $timestamp
     * @param string $key
     * @param bool   $echo
     * @param bool   $trans
     *
     * @return string|null Cas
     */
    public function timestamp($timestamp, $key, $echo = false, $trans = false) {

        switch($key) {
            case self::FORMAT_DATE:
                $format = 'j.n.Y';
                break;
            case self::FORMAT_TIME:
                $format = 'G:i';
                break;
            case self::FORMAT_TIME_SECOND:
                $format = 'G:i:s';
                break;
            case self::FORMAT_DATE_TIME:
                $format = 'j.n.Y G:i';
                break;
            case self::FORMAT_DATE_TIME_NO_YEAR:
                $format = 'j.n G:i';
                break;
            case self::FORMAT_DATE_TIME_SECOND:
                $format = 'j.n.Y G:i:s';
                break;
            default:
                $format = '';
                break;
        }
        $return = \DateUtil::format($timestamp, $format);

        if ($echo === true) {
            echo $return;
        } else if($echo === false) {
            return $return;
        }
        return null;
    }
}