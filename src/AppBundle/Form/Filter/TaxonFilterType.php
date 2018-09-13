<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Form\Filter;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class TaxonFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('mainTaxon', EntityType::class, [
            'label' => false,
            'placeholder' => $options['placeholder'],
            'class' => 'AppBundle:Taxon',
            'group_by' => 'parent',
            'choice_label' => 'name',
            'query_builder' => function (EntityRepository $entityRepository) use ($options) {
                $queryBuilder = $entityRepository->createQueryBuilder('o');
                $queryBuilder->orderBy('o.left');

                if (null !== $options['taxon_code']) {
                    $queryBuilder
                        ->join('o.root', 'rootTaxon')
                        ->where('rootTaxon.code = :code')
                        ->andWhere('o.parent IS NOT NULL')
                        ->setParameter('code', $options['taxon_code']);
                }

                return $queryBuilder;
            },
            'expanded' => false,

        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'taxons' => [],
                'taxon_code' => null,
                'placeholder' => '---',
            ])
            ->setAllowedTypes('taxons', ['array'])
            ->setAllowedTypes('placeholder', ['null', 'string'])
            ->setAllowedTypes('taxon_code', ['null', 'string']);
    }
}
