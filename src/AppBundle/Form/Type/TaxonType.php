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

use Sylius\Bundle\TaxonomyBundle\Form\Type\TaxonType as BaseTaxonType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class TaxonType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('public', ChoiceType::class, array(
                'label' => 'label.public',
                'choices'  => array(
                    'label.yes' => true,
                    'label.no' => false,
                ),
            ))
            ->add('iconClass', TextType::class, array(
                'label' => 'app.ui.icon_class',
                'required' => false,
            ))
            ->add('color', TextType::class, array(
                'label' => 'sylius.ui.color',
                'required' => false,
            ));
    }

    public function getParent()
    {
        return BaseTaxonType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'sylius_taxon';
    }
}
