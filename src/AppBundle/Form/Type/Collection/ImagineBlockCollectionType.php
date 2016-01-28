<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 28/01/2016
 * Time: 12:49
 */

namespace AppBundle\Form\Type\Collection;

use AppBundle\Form\Type\ImagineBlockType as BaseImagineBlockType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ImagineBlockCollectionType extends BaseImagineBlockType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->remove('name')
            ->remove('linkUrl')
            ->remove('publishable')
            ->remove('publishStartDate')
            ->remove('publishEndDate');
    }

    public function getName()
    {
        return 'app_collection_imagine_block';
    }
}