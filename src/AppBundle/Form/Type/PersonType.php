<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 01/04/16
 * Time: 20:30
 */

namespace AppBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class PersonType extends AbstractResourceType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mainImage', 'file', array(
                'label' => 'label.image',
                'required' => false,
            ))
            ->add('lastName', null, array(
                'label' => 'label.last_name',
            ))
            ->add('firstName', null, array(
                'label' => 'label.first_name',
                'required' => false,
            ))
            ->add('zone', 'entity', array(
                'label' => 'label.zone',
                'class' => 'AppBundle:Taxon',
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('o')
                        ->join('o.taxonomy', 'taxonomy')
                        ->join('taxonomy.root', 'rootTaxon')
                        ->where('rootTaxon.code = :code')
                        ->andWhere('o.parent IS NOT NULL')
                        ->setParameter('code', 'zones')
                        ->orderBy('o.left');
                },
                'expanded' => false,
                'multiple' => false,
                'placeholder' => 'Choisissez une zone',
                'required' => false,
            ))
            ->add('website', null, array(
                'label' => 'label.website',
                'required' => false,
            ))
            ->add('description', 'ckeditor', array(
                'label' => 'label.description',
                'required' => false,
            ))
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_person';
    }
}