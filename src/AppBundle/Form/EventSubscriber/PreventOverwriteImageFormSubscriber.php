<?php

/*
 * This file is part of jdj.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Form\EventSubscriber;

use AppBundle\Document\ImagineBlock;
use Symfony\Cmf\Bundle\MediaBundle\Doctrine\Phpcr\Image;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class PreventOverwriteImageFormSubscriber implements EventSubscriberInterface
{
    /**
     * @var Image
     */
    protected $existingImage;

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::POST_SET_DATA => 'onPostSetData',
            FormEvents::POST_SUBMIT   => 'onPostSubmit',
        );
    }

    /**
     * @param FormEvent $event
     */
    public function onPostSetData(FormEvent $event)
    {
        /** @var ImagineBlock $imagineBlock */
        $imagineBlock = $event->getData();

        if (null === $imagineBlock) {
            return;
        }

        $this->existingImage = $imagineBlock->getImage();
    }

    /**
     * @param FormEvent $event
     */
    public function onPostSubmit(FormEvent $event)
    {
        /** @var ImagineBlock $imagineBlock */
        $imagineBlock = $event->getData();

        if (null === $imagineBlock) {
            return;
        }

        // if image is filled, go on
        if (null !== $imagineBlock->getImage()) {
            return;
        }

        $imagineBlock->setImage($this->existingImage);
    }
}
