<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Form\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @see http://stackoverflow.com/questions/11499150/symfony2-forms-and-polymorphic-collections
 */
class PolymorphicCollectionSubscriber implements EventSubscriberInterface
{
    /**
     * @var FormFactoryInterface
     */
    protected $factory;

    /**
     * @var AbstractType
     */
    protected $type;

    /**
     * @var callback
     */
    protected $typeCallback;

    /**
     * @var array
     */
    protected $options;

    /**
     * PolymorphicCollectionSubscriber constructor.
     *
     * @param FormFactoryInterface $factory
     * @param AbstractType $type
     * @param AbstractType $childType
     * @param array $options
     */
    public function __construct(FormFactoryInterface $factory, AbstractType $type, AbstractType $childType, array $options)
    {
        $this->factory = $factory;
        $this->type = $type;
        $this->typeCallback = $childType;
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::POST_SET_DATA => 'handleChildTypes',
        ];
    }

    /**
     * @param FormEvent $event
     */
    public function handleChildTypes(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        // Go with defaults if we have no data
        if ($data === null || '' === $data) {
            return;
        }

        // It's possible to use array access/addChild, but it's not a part of the interface
        // Instead, we have to remove all children and re-add them to maintain the order
        $toAdd = array();

        foreach ($form as $name => $child) {
            // Store our own copy of the original form order, in case any are missing from the data
            $toAdd[$name] = $child->getConfig()->getOptions();
            $form->remove($name);
        }

        // Now that the form is empty, build it up again
        foreach ($toAdd as $name => $origOptions) {
            // Decide whether to use the default form type or some extension
            $datum = $data[$name] ?: null;
            $type = $this->type;

            if ($datum) {
                $calculatedType = call_user_func($this->typeCallback, $datum);

                if ($calculatedType) {
                    $type = $calculatedType;
                }
            }

            // And recreate the form field
            $form->add($this->factory->createNamed($name, $type, null, $origOptions));
        }
    }
}
