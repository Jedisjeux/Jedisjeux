<?php

/**
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Form\Type;

use App\Entity\FestivalList;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class FestivalListType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('name', TextType::class, [
                'label' => 'sylius.ui.name',
            ])
            ->add('image', FestivalListImageType::class, [
                'label' => false,
                'file_label' => 'sylius.ui.image',
            ])
            ->add('description', CKEditorType::class, [
                'label' => 'sylius.ui.description',
                'required' => false,
            ])
            ->add('start_at', DatePickerType::class, [
                'label' => 'sylius.ui.start_date',
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'required' => false,
            ])
            ->add('end_at', DatePickerType::class, [
                'label' => 'sylius.ui.end_date',
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'required' => false,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => FestivalList::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_festival_list';
    }
}
