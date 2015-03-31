<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 22/03/15
 * Time: 22:20
 */

namespace JDJ\CoreBundle\Form;


use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class LikeType
 * @package JDJ\CoreBundle\Form
 */
class LikeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('like', 'hidden')
            ->add('userReview', 'entity_id', array(
                'class' => 'JDJ\UserReviewBundle\Entity\UserReview',
                'query_builder' => function(EntityRepository $repo, $id) {
                    return $repo->createQueryBuilder('c')
                        ->where('c.id = :id')
                        ->setParameter('id', $id);
                },
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JDJ\CoreBundle\Entity\Like'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'jdj_corebundle_like';
    }
} 