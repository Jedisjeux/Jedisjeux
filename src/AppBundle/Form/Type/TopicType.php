<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 17/02/2016
 * Time: 17:21
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\Taxon;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class TopicType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('title', null, array(
                'label' => 'label.title',
            ))
            ->add('mainPost', 'app_post',  array(
                'label' => false,
            ))
            ->add('mainTaxon', 'entity', array(
                'label' => 'label.category',
                'class' => 'AppBundle:Taxon',
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('o')
                        ->join('o.root', 'rootTaxon')
                        ->where('rootTaxon.code = :code')
                        ->andWhere('o.parent IS NOT NULL')
                        ->setParameter('code', Taxon::CODE_FORUM)
                        ->orderBy('o.left');
                },
                'expanded' => false,
                'multiple' => false,
                'placeholder' => 'Choisissez une catégorie',
                'required' => false,
            ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Topic'
        ));
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'app_topic';
    }
}