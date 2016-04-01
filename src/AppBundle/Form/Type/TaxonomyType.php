<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 01/04/16
 * Time: 08:28
 */

namespace AppBundle\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class TaxonomyType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('code', null, array(
                'label' => 'label.code',
            ))
            ->add('name', null, array(
                'label' => 'label.name',
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sylius_taxonomy';
    }
}