<?php

namespace Proj\Base\Twig;

use Doctrine\Bundle\DoctrineBundle\Registry;
use nil\Html;
use nil\util\common\browser\BrowserUtil;
use Proj\Base\Entity\User;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Translation\Translator;

/**
 * Class MenuExtension
 *
 * @package Proj\Base\Twig
 */
class MenuExtension extends \Twig_Extension {

    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @var Registry
     */
    protected $doctrine;

    /**
     * @var Translator
     */
    protected $translator;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @param TokenStorageInterface                                                        $context
     * @param \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface $authChecker
     * @param Registry                                                                     $doctrine
     * @param Translator                                                                   $translator
     * @param Session                                                                      $session
     */
    public function __construct(TokenStorageInterface $context, AuthorizationCheckerInterface $authChecker, $doctrine, $translator, $session) {
        $this->tokenStorage = $context;
        $this->authChecker = $authChecker;
        $this->doctrine = $doctrine;
        $this->translator = $translator;
        $this->session = $session;
    }

    /**
     * @return array
     */
    public function getFunctions() {
        $token = $this->tokenStorage->getToken();
        $user = null;
        if ($token instanceof TokenInterface) {
            $user = $token->getUser();
        }
        if ($user instanceof User) {
            $functions = [
                new \Twig_SimpleFunction('isUser', function() use ($user) {
                    return true;
                }),
                new \Twig_SimpleFunction('selfUser', function() use ($user) {
                    return $user;
                }),
                new \Twig_SimpleFunction('infoServer', function() use ($user) {
                    $uname = posix_uname();
                    $infoBrowser = $uname['nodename'] . ' / Charon4';
                    return $infoBrowser;
                }),
                new \Twig_SimpleFunction('infoBrowser', function() use ($user) {
                    $infoBrowser = BrowserUtil::getInfoTextSmall() . ', ' . BrowserUtil::getInfoOsTextSmall();
                    return $infoBrowser;
                })
            ];
        } else {
            $functions = [
                new \Twig_SimpleFunction('isUser', function() use ($user) {
                    return false;
                })];
        }
        return $functions;
    }

    /**
     * @return array
     */
    public function getTests() {
        return [
            'instanceof' =>  new \Twig_Function_Method($this, 'isInstanceof')
        ];
    }

    /**
     * @param $var
     * @param $instance
     * @return bool
     */
    public function isInstanceof($var, $instance) {
        return  $var instanceof $instance;
    }

    /**
     * @return string
     */
    public function getName() {
        return 'menu_extension';
    }

}