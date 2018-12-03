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

use App\Entity\ProductBox;
use App\Entity\ProductVariant;
use Doctrine\ORM\EntityRepository;
use Sylius\Bundle\ProductBundle\Form\Type\ProductAutocompleteChoiceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceAutocompleteChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductBoxType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product', ProductAutocompleteChoiceType::class, [
                'label' => 'app.ui.game',
            ])
            ->add('image', ProductBoxImageType::class, [
                'label' => false,
            ])
            ->add('realHeight', NumberType::class, [
                'label' => 'app.ui.height_in_millimeter',
            ]);

        /** @var ProductBox $productBox */
        $productBox = $options['data'];

        if (null !== $productBox && null !== $productBox->getProduct()) {
            $builder
                ->add('productVariant', EntityType::class, [
                    'label' => 'sylius.ui.product_variants',
                    'required' => false,
                    'class' => ProductVariant::class,
                    'choice_name' => 'name',
                    'choice_value' => 'code',
                    'query_builder' => function (EntityRepository $er) use ($productBox) {
                        return $er->createQueryBuilder('o')
                            ->where('o.product = :product')
                            ->setParameter('product', $productBox->getProduct())
                            ->orderBy('o.position');
                    },
                ])
            ;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductBox::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_product_box';
    }
}
