<?php

namespace Proj\Base\Controller;

use Proj\Base\Object\Locale\SystemLang;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

/**
 */
class SecurityController extends BaseController {

    /**
     * @param Request $request
     * @return array []
     */
    public function indexAction(Request $request) {
        $session = $request->getSession();

        if ($request->isXmlHttpRequest()) {
            echo "<script>window.location = '/login';</script>";
            exit;
        }

        // get the login error if there is one
        if ($request->attributes->has(Security::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                Security::AUTHENTICATION_ERROR
            );
        } elseif (null !== $session && $session->has(Security::AUTHENTICATION_ERROR)) {
            $error = $session->get(Security::AUTHENTICATION_ERROR);
            $session->remove(Security::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(Security::LAST_USERNAME);

        return $this->render(
            '@ProjBase/Security/index.html.twig',
            [
                // last username entered by the user
                'last_username' => $lastUsername,
                'error'         => $error,
                'langArr'       => SystemLang::getLangArr(),
                'locale'        => $request->getLocale()
            ]
        );
    }
}