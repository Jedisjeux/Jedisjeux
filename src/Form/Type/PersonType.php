<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Form\Type;

use App\Entity\Taxon;
use Doctrine\ORM\EntityRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class PersonType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastName', null, [
                'label' => 'sylius.ui.last_name',
            ])
            ->add('firstName', null, [
                'label' => 'sylius.ui.first_name',
                'required' => false,
            ])
            ->add('zone', EntityType::class, [
                'label' => 'label.zone',
                'class' => 'App:Taxon',
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
            ])
            ->add('website', null, [
                'label' => 'label.website',
                'required' => false,
            ])
            ->add('description', CKEditorType::class, [
                'label' => 'label.description',
                'required' => false,
            ])
            ->add('images', CollectionType::class, [
                'entry_type' => PersonImageType::class,
                'label' => 'sylius.ui.images',
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'required' => false,
            ])

        ;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'app_person';
    }
}
