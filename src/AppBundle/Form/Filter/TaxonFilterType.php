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
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class TaxonFilterType extends AbstractType
{
    /**
     * @var AuthorizationCheckerInterface
     */
    protected $authorizationChecker;

    /**
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $onlyPublic = $this->authorizationChecker->isGranted('ROLE_STAFF') ? false : true;

        $builder->add('mainTaxon', EntityType::class, [
            'label' => false,
            'placeholder' => $options['placeholder'],
            'class' => 'AppBundle:Taxon',
            'group_by' => 'parent',
            'choice_value' => 'slug',
            'choice_label' => 'name',
            'query_builder' => function (EntityRepository $entityRepository) use ($options, $onlyPublic) {
                $queryBuilder = $entityRepository->createQueryBuilder('o');
                $queryBuilder->orderBy('o.left');

                if (null !== $options['taxon_code']) {
                    $queryBuilder
                        ->join('o.root', 'rootTaxon')
                        ->where('rootTaxon.code = :code')
                        ->andWhere('o.parent IS NOT NULL')
                        ->setParameter('code', $options['taxon_code']);
                }

                if ($onlyPublic) {
                    $queryBuilder
                        ->andWhere('o.public = :public')
                        ->setParameter('public', 1);
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
