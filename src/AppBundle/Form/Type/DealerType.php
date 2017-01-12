<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\Dealer;
use AppBundle\Entity\PubBanner;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Sonata\CoreBundle\Form\Type\CollectionType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class DealerType extends AbstractResourceType
{
    /**
     * @var Collection|PubBanner[]
     */
    protected $originalPubBanners;

    /**
     * @var EntityManager
     */
    protected $manager;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('code', TextType::class, [
                'label' => 'label.code',
            ])
            ->add('name', TextType::class, [
                'label' => 'label.name',
            ])
            ->add('image', 'app_dealer_image', [
                'label' => 'label.image',
                'required' => false,
            ])
            ->add('priceList', 'app_price_list', [
                'label' => 'label.price_list',
                'required' => false,
            ])
            ->add('pubBanners', 'collection', array(
                'label' => 'label.pub_banners',
                'type' => 'app_pub_banner',
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'widget_add_btn' => array('label' => "label.add_image"),
                'show_legend' => false, // dont show another legend of subform
                'options' => array( // options for collection fields
                    'label_render' => false,
                    'horizontal_input_wrapper_class' => "col-lg-8",
                    'widget_remove_btn' => array('label' => "label.remove_this_image"),
                )
            ))
            ->addEventListener(FormEvents::POST_SET_DATA, array($this, 'onPostSetData'))
            ->addEventListener(FormEvents::POST_SUBMIT, array($this, 'onPostSubmit'));
    }

    /**
     * @param FormEvent $event
     */
    public function onPostSetData(FormEvent $event)
    {
        /** @var Dealer $dealer */
        $dealer = $event->getData();

        $this->originalPubBanners = new ArrayCollection();

        foreach($dealer->getPubBanners() as $pubBanner) {
            $this->originalPubBanners->add($pubBanner);
        }
    }

    /**
     * @param FormEvent $event
     */
    public function onPostSubmit(FormEvent $event)
    {
        /** @var Dealer $dealer */
        $dealer = $event->getData();

        // remove pub banners not present in submit form
        foreach ($this->originalPubBanners as $pubBanner) {
            if (false === $dealer->getPubBanners()->contains($pubBanner)) {
                $dealer->removePubBanner($pubBanner);
                $this->manager->persist($pubBanner);
            }
        }
    }

    /**
     * @param EntityManager $manager
     */
    public function setManager($manager)
    {
        $this->manager = $manager;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_dealer';
    }
}
