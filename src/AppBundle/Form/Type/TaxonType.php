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

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\TaxonomyBundle\Form\EventListener\BuildTaxonFormSubscriber;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class TaxonType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('code', null, array(
                'label' => 'label.code',
            ))
            ->add('name', null, array(
                'label' => 'label.name',
            ))
            ->addEventSubscriber(new BuildTaxonFormSubscriber($builder->getFormFactory()));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sylius_taxon';
    }
}
