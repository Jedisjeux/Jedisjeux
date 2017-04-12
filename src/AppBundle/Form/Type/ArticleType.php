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

use AppBundle\Entity\Article;
use AppBundle\Entity\Block;
use AppBundle\Entity\Taxon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\TaxonomyBundle\Form\Type\TaxonAutocompleteChoiceType;
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
                'label' => 'label.title',
            ])
            ->add('mainImage', ArticleImageType::class, [
                'label' => false,
                'file_label' => 'app.ui.main_image',
            ])
            ->add('shortDescription', CKEditorType::class, [
                'label' => 'app.ui.short_description',
                'required' => false,
            ])
            ->add('mainTaxon', TaxonAutocompleteChoiceType::class, array(
                'label' => 'sylius.ui.category',
                'placeholder' => 'app.ui.choose_category',
                #'root' => Taxon::CODE_ARTICLE,
                'multiple' => false,
                'required' => true,
            ))
            ->add('blocks', CollectionType::class, [
                'label' => false,
                'entry_type' => BlockType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->addEventListener(FormEvents::POST_SET_DATA, array($this, 'onPostSetData'))
            ->addEventListener(FormEvents::POST_SUBMIT, array($this, 'onPostSubmit'));
    }

    /**
     * @param FormEvent $event
     */
    public function onPostSetData(FormEvent $event)
    {
        /** @var Article $article */
        $article = $event->getData();

        $this->originalBlocks = new ArrayCollection();

        foreach($article->getBlocks() as $block) {
            $this->originalBlocks->add($block);
        }
    }

    /**
     * @param FormEvent $event
     */
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
    public function getName()
    {
        return 'app_article';
    }
}
