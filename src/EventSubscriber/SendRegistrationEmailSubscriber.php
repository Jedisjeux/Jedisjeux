<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\EventSubscriber;

use App\Emails;
use Sylius\Component\Mailer\Sender\SenderInterface;
use Sylius\Component\Customer\Model\CustomerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class SendRegistrationEmailSubscriber implements EventSubscriberInterface
{
    /**
     * @var SenderInterface
     */
    protected $sender;

    /**
     * @param SenderInterface $sender
     */
    public function __construct(SenderInterface $sender)
    {
        $this->sender = $sender;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'sylius.customer.post_register' => 'onRegister',
        );
    }

    public function onRegister(GenericEvent $event)
    {
        /** @var CustomerInterface $customer */
        $customer = $event->getSubject();

        $this->sender->send(Emails::USER_REGISTRATION, array($customer->getEmail()), array(
            'customer' => $customer,
        ));
    }
}
