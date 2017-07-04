<?php

/*
 * This file is part of jdj.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Form\Extension;

use AppBundle\Form\Type\ProductVariantType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductVariantTypeExtension extends AbstractTypeExtension
{
    /**
     * @var EventSubscriberInterface
     */
    protected $addPersonFormSubscriber;

    /**
     * ProductVariantTypeExtension constructor.
     *
     * @param EventSubscriberInterface $addPersonFormSubscriber
     */
    public function __construct(EventSubscriberInterface $addPersonFormSubscriber)
    {
        $this->addPersonFormSubscriber = $addPersonFormSubscriber;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventSubscriber($this->addPersonFormSubscriber);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return ProductVariantType::class;
    }
}
