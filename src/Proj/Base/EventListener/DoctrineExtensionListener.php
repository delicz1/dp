<?php
/**
 *
 */

namespace Proj\Base\EventListener;

use Gedmo\Loggable\LoggableListener;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Class DoctrineExtensionListener
 *
 * @package Proj\Base\EventListener
 */
class DoctrineExtensionListener implements ContainerAwareInterface {

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onLateKernelRequest(GetResponseEvent $event) {
//        $translatable = $this->container->get('gedmo.listener.translatable');
//        $translatable->setTranslatableLocale($event->getRequest()->getLocale());
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(/** @noinspection PhpUnusedParameterInspection */ GetResponseEvent $event) {
        $tokenStorage = $this->container->get('security.token_storage');
        if (null !== $tokenStorage && null !== $tokenStorage->getToken()) {
            /** @var LoggableListener $loggable */
            $loggable = $this->container->get('gedmo.listener.loggable');
            $loggable->setUsername($tokenStorage->getToken()->getUsername());
        }
    }
}