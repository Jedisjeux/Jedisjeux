<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class DealerType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('code', null, [
                'label' => 'label.code',
            ])
            ->add('name', null, [
                'label' => 'label.name',
            ])
            ->add('image', 'file', [
                'label' => 'label.image',
                'required' => false,
            ])
            ->add('active', ChoiceType::class, [
                'label' => 'label.active',
                'choices' => array(
                    'label.yes' => true,
                    'label.no' => false,
                ),
                'expanded' => true,
                'choices_as_values' => true,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_dealer';
    }
}
