<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Form\Extension;

use App\Form\EventSubscriber\AddUserFormSubscriber;
use App\Form\Type\Customer\CustomerType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;

final class CustomerTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventSubscriber(new AddUserFormSubscriber());
    }

    /**
     * {@inheritdoc}
     */
    public static function getExtendedTypes()
    {
        return [CustomerType::class];
    }
}
