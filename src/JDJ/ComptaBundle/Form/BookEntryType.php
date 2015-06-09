<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 26/05/2015
 * Time: 16:04
 */

namespace JDJ\ComptaBundle\Form;


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
            ->add('price', 'money', array(
                'label' => 'label.price',
            ))
            ->add('paymentMethod', null, array(
                'label' => 'label.payment_method'
            ))
            ->add('creditedOrDebited', 'choice', array(
                'label' => 'label.credited_or_debited',
                'choices' => array(
                    'debited' => 'Débit',
                    'credited' => 'Crédit',
                ),
                'mapped' => false,
            ))
            ->add('creditedAt', null, array(
                'label' => 'label.creditedAt',
            ))
            ->add('debitedAt', null, array(
                'label' => 'label.debitedAt',
            ))
            ->add('label', null, array(
                'label' => 'label',
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