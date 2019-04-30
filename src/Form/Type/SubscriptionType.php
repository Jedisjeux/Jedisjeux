<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\SubscriptionInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class SubscriptionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var SubscriptionInterface $subscription */
        $subscription = $options['data'];

        $builder->add('options', ChoiceType::class, [
            'label' => 'sylius.ui.options',
            'multiple' => true,
            'expanded' => true,
            'choices' => $this->createOptionsList($subscription),
        ]);
    }

    private function createOptionsList(SubscriptionInterface $subscription): array
    {
        $values = [];

        foreach ($subscription->getOptions() as $option) {
            $key = sprintf('%s%s', 'app.ui.', $option);
            $values[$key] = $option;
        }

        return $values;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_subscription';
    }
}
