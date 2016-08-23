<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Form\Type;

use AppBundle\Form\EventSubscriber\PolymorphicCollectionSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class PolymorphicCollectionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Tack on our event subscriber
        $builder->addEventSubscriber(new PolymorphicCollectionSubscriber($builder->getFormFactory(), $options['type'], $options['type_callback'], []));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return "collection";
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array('type_callback'));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_polymorphic_collection';
    }
}
