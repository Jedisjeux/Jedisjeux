<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 31/08/2014
 * Time: 12:34
 */

namespace JDJ\UserReviewBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class JeuNoteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('note')
            ->add('jeu', 'entity_id', array(
                'class' => 'JDJ\JeuBundle\Entity\Jeu',
                'query_builder' => function(EntityRepository $repo, $id) {
                    return $repo->createQueryBuilder('c')
                        ->where('c.id = :id')
                        ->setParameter('id', $id);
                }
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JDJ\UserReviewBundle\Entity\JeuNote',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'jdj_userreviewbundle_jeunote';
    }
} 