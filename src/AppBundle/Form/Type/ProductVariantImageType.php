<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 05/04/2016
 * Time: 13:18
 */

namespace AppBundle\Form\Type;

use AppBundle\Form\EventSubscriber\ImageUploadSubscriber;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductVariantImageType extends AbstractImageType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('description', null, array(
                'required' => false,
                'label' => 'label.description',
            ))
            ->add('main', null, array(
                'required' => false,
                'label' => 'label.main',
            ))
            ->add('material', null, array(
                'required' => false,
                'label' => 'label.material',
            ))
            ->addEventSubscriber(new ImageUploadSubscriber());
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_product_variant_image';
    }
}