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

use AppBundle\Document\BlockquoteBlock;
use AppBundle\Document\SingleImageBlock;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ArticleContentType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $typeCallback = function ($datum) {
            if ($datum instanceof SingleImageBlock) {
                return "app_single_image_block";
            } elseif ($datum instanceof BlockquoteBlock) {
                return "app_blockquote_block";
            } else {
                return null; // Returning null tells the polymorphic collection to use the default type - can be omitted, but included here for clarity
            }
        };

        $builder
            ->add('name')
            ->add('title')
            /*->add('children', 'collection', [
                'type' => 'app_single_image_block',
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false,
                'cascade_validation' => true,
            ])*/
            ->add('children', 'app_polymorphic_collection', [
                'type_callback' => $typeCallback,  /* Used for determining the per-item type */
                'type' => 'app_single_image_block', /* Used as a fallback and for prototypes */
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'cascade_validation' => true,
            ]);
    }

    public function getName()
    {
        return 'app_article_content';
    }
}
