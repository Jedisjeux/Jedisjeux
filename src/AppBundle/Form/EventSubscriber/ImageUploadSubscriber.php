<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 06/04/16
 * Time: 00:33
 */

namespace AppBundle\Form\EventSubscriber;

use AppBundle\Entity\AbstractImage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ImageUploadSubscriber implements EventSubscriberInterface
{
    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::POST_SUBMIT   => 'onPostSubmit',
        ];
    }

    /**
     * @param FormEvent $event
     */
    public function onPostSubmit(FormEvent $event)
    {
        $abstractImage = $event->getData();

        if (!$abstractImage instanceof AbstractImage) {
            return;
        }

        $abstractImage->upload();
    }
}
