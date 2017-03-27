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
use AppBundle\Entity\Taxon;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\TaxonomyBundle\Form\Type\TaxonChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ArticleType extends AbstractResourceType
{
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
            ->add('mainTaxon', TaxonChoiceType::class, array(
                'label' => 'sylius.ui.category',
                'placeholder' => 'app.ui.choose_category',
                'root' => Taxon::CODE_ARTICLE,
                'multiple' => false,
                'required' => true,
            ))
            ->add('blocks', CollectionType::class, [
                'label' => false,
                'entry_type' => BlockType::class,
                'allow_add' => true,
                'allow_delete' => true,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => $this->dataClass,
            'validation_groups' => $this->validationGroups,
            'cascade_validation' => true,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_article';
    }
}
