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
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

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
            ->add('document', 'app_article_content')
            ->add('status', ChoiceType::class, [
                'label' => 'label.status',
                'required' => true,
                'choices' => [
                    'label.new' => Article::STATUS_NEW,
                    'label.need_a_review' => Article::STATUS_NEED_A_REVIEW,
                    'label.ready_to_publish' => Article::STATUS_READY_TO_PUBLISH,
                    'label.published' => Article::STATUS_PUBLISHED,
                ],
                'choices_as_values' => true,
            ]);
    }

    public function getName()
    {
        return 'app_article';
    }
}
