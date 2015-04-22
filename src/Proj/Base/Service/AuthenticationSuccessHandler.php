<?php
/**
 * @author springer
 */

namespace Proj\Base\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Proj\Base\Entity\User;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationSuccessHandler;

/**
* Class AuthenticationSuccessHandler
 * @package Proj\Base\Service
 */
class AuthenticationSuccessHandler extends DefaultAuthenticationSuccessHandler {

    /**
     * @var Registry
     */
    private $doctrine;

    /**
     * @param HttpUtils $httpUtils
     * @param Registry $doctrine
     */
    public function __construct(HttpUtils $httpUtils, Registry $doctrine) {
        $this->doctrine = $doctrine;
        parent::__construct($httpUtils, []);
    }

    /**
     * @param Request        $request
     * @param TokenInterface $token
     *
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response|void
     */
    public function onAuthenticationSuccess( Request $request, TokenInterface $token ) {
        /** @var User $user */
        dump($request);
//        $user = $token->getUser();
//        $session = $request->getSession();
//        $groupId = $user->getActiveGroupId();
//        $dbName = $this->doctrine->getRepository('RdrBaseBundle:Group')->find($groupId)->getDbName();
//        $locale = $user->getConfig(UserConfig::KEY_ACTIVE_LOCALE);
//        $session->set('GROUP_ID', $groupId);
//        $session->set('DB_NAME', $dbName);
//        $session->set('_locale', $locale);
        return parent::onAuthenticationSuccess($request, $token);
    }
}