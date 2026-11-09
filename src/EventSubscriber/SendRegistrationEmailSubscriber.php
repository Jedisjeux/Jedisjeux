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
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Mailer\Sender\SenderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class SendRegistrationEmailSubscriber implements EventSubscriberInterface
{
    public function __construct(private SenderInterface $sender)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.customer.post_register' => 'onRegister',
        ];
    }

    public function onRegister(GenericEvent $event): void
    {
        /** @var CustomerInterface $customer */
        $customer = $event->getSubject();

        $this->sender->send(Emails::USER_REGISTRATION, [$customer->getEmail()], [
            'customer' => $customer,
        ]);
    }
}
