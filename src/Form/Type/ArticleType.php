<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Form\Type;

use App\Entity\Article;
use App\Entity\Block;
use App\Entity\Taxon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sylius\Bundle\ProductBundle\Form\Type\ProductAutocompleteChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ArticleType extends AbstractType
{
    /**
     * @var Collection|Block[]
     */
    protected $originalBlocks;

    /**
     * @var EntityManager
     */
    protected $manager;

    /**
     * @param EntityManager $manager
     */
    public function setManager($manager)
    {
        $this->manager = $manager;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('title', TextType::class, [
                'label' => 'sylius.ui.title',
            ])
            ->add('mainImage', ArticleImageType::class, [
                'label' => false,
                'file_label' => 'app.ui.main_image',
            ])
            ->add('shortDescription', CKEditorType::class, [
                'label' => 'app.ui.short_description',
                'required' => false,
            ])
            ->add('mainTaxon', EntityType::class, [
                'label' => 'sylius.ui.category',
                'placeholder' => '---',
                'class' => 'App:Taxon',
                'group_by' => 'parent',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('o')
                        ->join('o.root', 'rootTaxon')
                        ->where('rootTaxon.code = :code')
                        ->andWhere('o.parent IS NOT NULL')
                        ->setParameter('code', Taxon::CODE_ARTICLE)
                        ->orderBy('o.position');
                },
                'multiple' => false,
                'required' => false,
            ])
            ->add('product', ProductAutocompleteChoiceType::class, [
                'label' => 'sylius.ui.product',
                'required' => false,
            ])
            ->add('blocks', CollectionType::class, [
                'label' => false,
                'entry_type' => BlockType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->add('materialRating', ChoiceType::class, [
                'required' => false,
                'label' => 'app.ui.material_rating',
                'choices' => $this->getRatingChoiceValues(),
            ])
            ->add('rulesRating', ChoiceType::class, [
                'required' => false,
                'label' => 'app.ui.rules_rating',
                'choices' => $this->getRatingChoiceValues(),
            ])
            ->add('lifetimeRating', ChoiceType::class, [
                'required' => false,
                'label' => 'app.ui.lifetime_rating',
                'choices' => $this->getRatingChoiceValues(),
            ])
            ->addEventListener(FormEvents::POST_SET_DATA, [$this, 'onPostSetData'])
            ->addEventListener(FormEvents::POST_SUBMIT, [$this, 'onPostSubmit']);
    }

    /**
     * @return array
     */
    protected function getRatingChoiceValues()
    {
        return [
            'Choisissez une note' => 0,
            '1/10 - Injouable' => 0.5,
            '2/10 - Très mauvais' => 1,
            '3/10 - Mauvais' => 1.5,
            '4/10 - Bof' => 2,
            '5/10 - Moyen' => 2.5,
            '6/10 - Pas mal' => 3,
            '7/10 - Bon' => 3.5,
            '8/10 - Très bon' => 4,
            '9/10 - Excellent' => 4.5,
            '10/10 - Mythique' => 5,
        ];
    }

    public function onPostSetData(FormEvent $event)
    {
        /** @var Article $article */
        $article = $event->getData();

        $this->originalBlocks = new ArrayCollection();

        foreach ($article->getBlocks() as $block) {
            $this->originalBlocks->add($block);
        }
    }

    public function onPostSubmit(FormEvent $event)
    {
        /** @var Article $article */
        $article = $event->getData();

        // remove pub banners not present in submit form
        foreach ($this->originalBlocks as $block) {
            if (false === $article->hasBlock($block)) {
                $article->removeBlock($block);
                $this->manager->remove($block);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_article';
    }
}
