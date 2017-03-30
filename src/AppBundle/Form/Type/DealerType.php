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
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ->add('image', DealerImageType::class, [
                'label' => false,
                'required' => false,
            ])
            ->add('priceList', PriceListType::class, [
                'label' => false,
                'required' => false,
            ])
            ->add('pubBanners', CollectionType::class, array(
                'label' => false,
                'entry_type' => PubBannerType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,

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
                $this->manager->remove($pubBanner);
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
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => $this->dataClass,
            'validation_groups' => $this->validationGroups,
            'cascade_validation' => true,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_dealer';
    }
}
