<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\GamePlay;
use AppBundle\Entity\GamePlayImage;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class GamePlayType extends AbstractType
{
    /**
     * @var Collection|GamePlayImage[]
     */
    protected $originalImages;

    /**
     * @var EntityManager
     */
    protected $manager;

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
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('playedAt', null, [
                'label' => 'label.played_at',
                'required' => false,
                'widget' => 'single_text',
                'widget_addon_append' => [
                    'icon' => 'calendar'
                ],
                'html5' => false,
                'attr' => [
                    'class' => 'date',
                ]
            ])
            ->add('duration', IntegerType::class, [
                'label' => 'label.duration',
                'required' => false,
                'widget_addon_append' => [
                    'icon' => 'time'
                ],
                'help_label' => '(en minutes)',
            ])
            ->add('playerCount', null, [
                'label' => 'label.player_count',
                'required' => false,
            ])
            ->add('images', CollectionType::class, [
                'entry_type' => GamePlayImageType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'widget_add_btn' => ['label' => "label.add_image"],
                'show_legend' => false, // dont show another legend of subform
                'entry_options' => [ // options for collection fields
                    'label_render' => false,
                    'horizontal_input_wrapper_class' => "col-lg-8",
                    'widget_remove_btn' => ['label' => "label.remove_this_image"],
                ]
            ])
            ->add('players', CollectionType::class, [
                'entry_type' => PlayerType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'widget_add_btn' => ['label' => "label.add_player"],
                'show_legend' => false, // dont show another legend of subform
                'entry_options' => [ // options for collection fields
                    'label_render' => false,
                    'horizontal_input_wrapper_class' => "col-lg-8",
                    'widget_remove_btn' => ['label' => "label.remove_this_player"],
                ]
            ])
            ->addEventListener(FormEvents::POST_SET_DATA, array($this, 'onPostSetData'))
            ->addEventListener(FormEvents::POST_SUBMIT, array($this, 'onPostSubmit'));
    }

    /**
     * @param FormEvent $event
     */
    public function onPostSetData(FormEvent $event)
    {
        /** @var GamePlay $gamePlay */
        $gamePlay = $event->getData();

        $this->originalImages = new ArrayCollection();

        foreach($gamePlay->getImages() as $image) {
            $this->originalImages->add($image);
        }
    }

    /**
     * @param FormEvent $event
     */
    public function onPostSubmit(FormEvent $event)
    {
        /** @var GamePlay $gamePlay */
        $gamePlay = $event->getData();

        // remove images not present in submit form
        foreach ($this->originalImages as $image) {
            if (false === $gamePlay->getImages()->contains($image)) {
                $gamePlay->removeImage($image);
                $this->manager->remove($image);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => GamePlay::class,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_game_play';
    }
}
