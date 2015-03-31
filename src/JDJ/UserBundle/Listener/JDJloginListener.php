<?php

namespace JDJ\UserBundle\Listener;


use Doctrine\Common\Util\Debug;
use FOS\UserBundle\Event\UserEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class JDJLoginListener implements EventSubscriberInterface
{
    public function onLogin(UserEvent $event)
    {
        return $this->redirect($this->generateUrl('my_collections'));
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::SECURITY_IMPLICIT_LOGIN => 'onSecurityImplicitLogin',
            FOSUserEvents::REGISTRATION_COMPLETED=> 'onRegistrationCompleted'
        );
    }
}