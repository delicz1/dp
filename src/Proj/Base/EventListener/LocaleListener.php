<?php
/**
 * @author necas
 */
// src/Acme/LocaleBundle/EventListener/LocaleListener.php
namespace Proj\Base\EventListener;

use Proj\Base\Object\Locale\SystemLang;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class LocaleListener
 *
 * @package Proj\Base\EventListener
 */
class LocaleListener implements EventSubscriberInterface {

    private $defaultLocale;

    /**
     * @param string $defaultLocale
     */
    public function __construct($defaultLocale) {
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event) {
        $request = $event->getRequest();
        if (!$request->hasPreviousSession()) {
            return;
        }

        // try to see if the locale has been set as a _locale routing parameter
        if ($locale = $request->attributes->get('_locale')) {
            $request->getSession()->set('_locale', $locale);
        } else {
            // if no explicit locale has been set on this request, use one from the session
            $localeHeader = $request->getPreferredLanguage(array_keys(SystemLang::getLangArr()));
//            var_dump('Header:' . $localeHeader);
            $localeCookie = $request->cookies->get('__locale', $localeHeader);
            $localeSession = $request->getSession()->get('_locale', $localeCookie);
//            var_dump('Cookie:' . $localeCookie);
//            var_dump('Session:' . $localeSession);
            $request->setLocale($localeSession);
        }
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents() {
        return [
            // must be registered before the default Locale listener
            KernelEvents::REQUEST => [['onKernelRequest', 17]],
        ];
    }
}