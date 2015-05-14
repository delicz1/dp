<?php

namespace Proj\Base\Controller;

use Proj\Base\Entity\User;
use Proj\Base\Object\Locale\Formatter;
use Proj\Base\Object\Locale\SystemLang;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Proj\Base\Object\Locale\LangTranslator;
use Symfony\Component\Translation\Translator;

/**
 */
class BaseController extends Controller {

    //=====================================================
    //== Konstanty ========================================
    //=====================================================

    /**
     * @var \Request
     */
    private $request;

    //=====================================================
    //== Konstruktor ======================================
    //=====================================================

    /**
     *
     */
    public function __construct() {
        $this->request = new \Request();
    }

    //=====================================================
    //== Helpery ==========================================
    //=====================================================

    /**
     * @return \Proj\Base\Entity\User
     */
    protected function getSelfUser() {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        return $user;
    }

    /**
     * @return LangTranslator
     */
    protected function getLangTranslator() {
        /** @var Translator $translator */
        $translator = $this->get('translator');
        $langTranslator = new LangTranslator();
        $langTranslator->setTranslator($translator);
        return $langTranslator;
    }

    /**
     * @return SystemLang
     */
    protected function getSystemLang() {
        /** @var Translator $translator */
        $translator = $this->get('translator');
        return (new SystemLang())->setIso($translator->getLocale());
    }

    /**
     * @return Formatter
     */
    protected function getFormater() {
        return Formatter::getInstance($this->getLangTranslator());
    }

    /**
     * @return \Request
     */
    protected function getRequestNil() {
        return $this->request;
    }
}