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

use AppBundle\Entity\Taxon;
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
            ->add('mainImage', 'app_person_image', [
                'label' => 'label.image',
                'required' => false,
            ])
            ->add('lastName', null, [
                'label' => 'label.last_name',
            ])
            ->add('firstName', null, [
                'label' => 'label.first_name',
                'required' => false,
            ])
            ->add('zone', 'entity', array(
                'label' => 'label.zone',
                'class' => 'AppBundle:Taxon',
                'group_by' => 'parent',
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('o')
                        ->join('o.root', 'rootTaxon')
                        ->where('rootTaxon.code = :code')
                        ->andWhere('o.parent IS NOT NULL')
                        ->setParameter('code', Taxon::CODE_ZONE)
                        ->orderBy('o.left');
                },
                'expanded' => false,
                'multiple' => false,
                'placeholder' => 'Choisissez une zone',
                'required' => false,
            ))
            ->add('website', null, [
                'label' => 'label.website',
                'required' => false,
            ])
            ->add('description', 'ckeditor', [
                'label' => 'label.description',
                'required' => false,
            ])
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
