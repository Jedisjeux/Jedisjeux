<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 26/05/2015
 * Time: 16:04
 */

namespace JDJ\ComptaBundle\Form;


use JDJ\ComptaBundle\Entity\BookEntry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class BookEntryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label', null, array(
                'label' => 'label',
            ))
            ->add('price', 'money', array(
                'label' => 'label.amount',
            ))
            ->add('paymentMethod', null, array(
                'label' => 'label.payment_method',
            ))
            ->add('state', 'choice', array(
                'label' => 'label.state',
                'choices' => array(
                    BookEntry::STATE_DEBITED => 'débité',
                    BookEntry::STATE_CREDITED => 'crédité',
                ),
            ))
            ->add('activeAt', null, array(
                'label' => 'label.date',
                'widget' => 'single_text',
                'html5' => false,
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JDJ\ComptaBundle\Entity\BookEntry'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'jdj_comptabundle_book_entry';
    }
}