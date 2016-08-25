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

use Sylius\Bundle\ReviewBundle\Form\Type\ReviewType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductReviewType extends ReviewType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rating', 'hidden', [
                'label' => 'label.rate',
            ])
            ->add('author', 'sylius_customer_guest', [
                'label' => false,
            ])
            ->add('title', 'text', [
                'label' => 'label.title',
            ])
            ->add('comment', 'ckeditor', [
                'label' => 'label.comment',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'rating_steps' => 10,
            'validation_groups' => $this->validationGroups,
        ]);
    }

    /**
     * @param int $maxRate
     *
     * @return array
     */
    protected function createRatingList($maxRate)
    {
        $ratings = [];
        for ($i = 1; $i <= $maxRate; ++$i) {
            $ratings[$i] = $i;
        }

        return $ratings;
    }
}
