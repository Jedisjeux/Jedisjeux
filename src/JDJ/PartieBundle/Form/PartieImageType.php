<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 24/03/15
 * Time: 13:33
 */

namespace JDJ\PartieBundle\Form;


use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PartieImageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('partie', 'entity_id', array(
                'class' => 'JDJ\PartieBundle\Entity\Partie',
                'query_builder' => function(EntityRepository $repo, $id) {
                    return $repo->createQueryBuilder('c')
                        ->where('c.id = :id')
                        ->setParameter('id', $id);
                }
            ))
            ->add('image', 'jdj_corebundle_image')
            ->add('description')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JDJ\PartieBundle\Entity\PartieImage'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'jdj_corebundle_partie_image';
    }
}