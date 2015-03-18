<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 16/03/15
 * Time: 13:50
 */

namespace JDJ\JeuBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class JeuImageType
 * @package JDJ\JeuBundle\Form
 */
class JeuImageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('jeu', 'entity_id', array(
                'class' => 'JDJ\JeuBundle\Entity\Jeu',
                'query_builder' => function(EntityRepository $repo, $id) {
                    return $repo->createQueryBuilder('c')
                        ->where('c.id = :id')
                        ->setParameter('id', $id);
                }
            ))
            ->add('image', 'jdj_corebundle_image')
            ->add('description')
            ->add('imageProperties', 'choice', array(
                'choices'   => array(
                    'image_couverture'   => 'Utiliser cette photo pour la couverture',
                    'material_image' => 'Utiliser cette photo pour illustrer le matériel du jeu',
                ),
                'expanded' => true,
                'multiple'  => true,
                'mapped' => false,
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JDJ\JeuBundle\Entity\JeuImage'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'jdj_corebundle_jeu_image';
    }
}