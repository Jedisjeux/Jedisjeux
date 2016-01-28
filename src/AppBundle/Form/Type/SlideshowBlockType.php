<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 26/01/2016
 * Time: 17:37
 */

namespace AppBundle\Form\Type;

use AppBundle\Form\Type\Collection\ImagineBlockCollectionType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\ImagineBlock;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\SlideshowBlock;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class SlideshowBlockType extends AbstractType
{
    /**
     * @var ImagineBlock[]
     */
    private $originalImagineBlocks;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options = array())
    {
        $builder
            ->add('name', 'text', array(
                'label' => 'label.internal_name'
            ))
            ->add('title', 'text', array(
                'label' => 'label.title'
            ))
            ->add('children', 'collection', array(
                'type' => new ImagineBlockCollectionType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'widget_add_btn' => array('label' => "label.add_slide"),
                'show_legend' => false,
                'cascade_validation' => true,
                'options' => array( // options for collection fields
                    'label_render' => false,
                    'horizontal_input_wrapper_class' => "col-lg-8",
                    'widget_remove_btn' => array(
                        'label' => "label.remove_this_slide",
                    ),
                )
            ))
            ->add('publishable', null, array(
                'label' => 'label.publishable'
            ))
            ->add('publishStartDate', 'datetime', array(
                'label' => 'label.publish_start_date',
                'widget' => 'single_text',
                'html5' => false,
                'required' => false,
                'attr' => array(
                    'class' => 'datetime',
                )
            ))
            ->add('publishEndDate', 'datetime', array(
                'label' => 'label.publish_end_date',
                'widget' => 'single_text',
                'html5' => false,
                'required' => false,
                'attr' => array(
                    'class' => 'datetime',
                )
            ))
            ->addEventListener(FormEvents::POST_SET_DATA, array($this, 'onPostSetData'))
            ->addEventListener(FormEvents::POST_SUBMIT, array($this, 'onPostSubmit'));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\SlideshowBlock'
        ));
    }

    /**
     * @param FormEvent $event
     */
    public function onPostSetData(FormEvent $event)
    {
        /** @var SlideshowBlock $slideshowBlock */

        $slideshowBlock = $event->getData();

        $this->originalImagineBlocks = new ArrayCollection();

        // Create an ArrayCollection of the current imagine block objects in the database
        foreach ($slideshowBlock->getChildren() as $imagineBlock) {
            $this->originalImagineBlocks->add($imagineBlock);
        }
    }

    /**
     * @param FormEvent $event
     */
    public function onPostSubmit(FormEvent $event)
    {
        /** @var SlideshowBlock $slideshowBlock */
        $slideshowBlock = $event->getData();

        // remove imagine blocks not present in submit form
        foreach ($this->originalImagineBlocks as $imagineBlock) {
            if ($slideshowBlock->getChildren()->contains($imagineBlock) == false) {
                // remove the ImagineBlock
                //$this->entityManager->remove($imagineBlock);
            }
        }

        /** @var ImagineBlock $imagineBlock */
        foreach ($slideshowBlock->getChildren() as $imagineBlock) {
            $imagineBlock
                ->setName(sprintf($slideshowBlock->getName() . '_%s', uniqid()))
                ->setParentDocument($slideshowBlock);
        }
    }

    public function getName()
    {
        return 'app_slideshow_block';
    }
}