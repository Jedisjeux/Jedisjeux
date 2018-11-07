<?php

/**
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\EventSubscriber;

use App\Emails;
use App\Entity\ContactRequest;
use App\Event\ContactRequestEvents;
use Sylius\Component\Mailer\Sender\SenderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Webmozart\Assert\Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class SendContactEmailSubscriber implements EventSubscriberInterface
{
    /**
     * @var SenderInterface
     */
    protected $sender;

    /**
     * @var string
     */
    protected $contactEmail;

    /**
     * SendContactEmailSubscriber constructor.
     *
     * @param SenderInterface $sender
     * @param string          $contactEmail
     */
    public function __construct(SenderInterface $sender, string $contactEmail)
    {
        $this->sender = $sender;
        $this->contactEmail = $contactEmail;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            ContactRequestEvents::POST_CREATE => 'onPostCreate',
        ];
    }

    /**
     * @param GenericEvent $event
     */
    public function onPostCreate(GenericEvent $event)
    {
        /** @var ContactRequest $contactRequest */
        $contactRequest = $event->getSubject();
        Assert::isInstanceOf($contactRequest, ContactRequest::class);

        $this->sender->send(Emails::CONTACT_REQUEST,
            [$this->contactEmail],
            ['contact_request' => $contactRequest],
            [],
            [$contactRequest->getEmail()]
        );
    }
}
