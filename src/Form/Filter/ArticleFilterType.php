<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Form\Filter;

use App\Entity\Article;
use App\Entity\Taxon;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ArticleFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('query', TextType::class, [
                'label' => 'label.search',
                'required' => false,
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'label.status',
                'required' => false,
                'choices' => [
                    'label.new' => Article::STATUS_NEW,
                    'label.pending_review' => Article::STATUS_PENDING_REVIEW,
                    'label.pending_publication' => Article::STATUS_PENDING_PUBLICATION,
                    'label.published' => Article::STATUS_PUBLISHED,
                ],
                'choices_as_values' => true,
            ])
            ->add('mainTaxon', 'entity', array(
                'label' => 'label.category',
                'class' => 'App:Taxon',
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('o')
                        ->join('o.root', 'rootTaxon')
                        ->where('rootTaxon.code = :code')
                        ->andWhere('o.parent IS NOT NULL')
                        ->setParameter('code', Taxon::CODE_ARTICLE)
                        ->orderBy('o.left');
                },
                'expanded' => false,
                'multiple' => false,
                'placeholder' => '',
                'required' => false,
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => null,
                'criteria' => null,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_filter_article';
    }
}
