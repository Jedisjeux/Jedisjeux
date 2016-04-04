<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 04/04/16
 * Time: 08:12
 */

namespace AppBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('name', null, array(
                'label' => 'label.name',
            ))
            ->add('mechanisms', 'entity', array(
                'label' => 'label.mechanisms',
                'class' => 'AppBundle:Taxon',
                'attr' => array(
                    'class' => 'multiple-select',
                ),
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('o')
                        ->join('o.taxonomy', 'taxonomy')
                        ->join('taxonomy.root', 'rootTaxon')
                        ->where('rootTaxon.code = :code')
                        ->andWhere('o.parent IS NOT NULL')
                        ->setParameter('code', 'mechanisms')
                        ->orderBy('o.left');
                },
                'expanded' => false,
                'multiple' => true,
                'placeholder' => 'Choisissez un mécanisme',
                'required' => false,
            ))
            ->add('themes', 'entity', array(
                'label' => 'label.themes',
                'class' => 'AppBundle:Taxon',
                'attr' => array(
                    'class' => 'multiple-select',
                ),
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('o')
                        ->join('o.taxonomy', 'taxonomy')
                        ->join('taxonomy.root', 'rootTaxon')
                        ->where('rootTaxon.code = :code')
                        ->andWhere('o.parent IS NOT NULL')
                        ->setParameter('code', 'themes')
                        ->orderBy('o.left');
                },
                'expanded' => false,
                'multiple' => true,
                'placeholder' => 'Choisissez un thème',
                'required' => false,
            ))
            ->add('shortDescription', 'ckeditor', array(
                'label' => 'label.short_description',
            ))
            ->add('description', 'ckeditor', array(
                'label' => 'label.description',
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sylius_product';
    }
}