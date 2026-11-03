<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\DependencyInjection\Compiler;

use Doctrine\Common\EventSubscriber;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Symfony 7 dropped the "doctrine.event_subscriber" tag, which the Sylius bundles and the application subscribers still use.
 * Each subscribed event becomes a "doctrine.event_listener" tag calling the method named after the event, as Doctrine did.
 */
final class DoctrineEventSubscriberPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        foreach ($container->findTaggedServiceIds('doctrine.event_subscriber', true) as $id => $tags) {
            $definition = $container->getDefinition($id);
            $class = $container->getParameterBag()->resolveValue($definition->getClass() ?? $id);

            /** @var EventSubscriber $subscriber */
            $subscriber = (new \ReflectionClass($class))->newInstanceWithoutConstructor();

            foreach ($tags as $attributes) {
                foreach ($subscriber->getSubscribedEvents() as $event) {
                    $definition->addTag('doctrine.event_listener', ['event' => $event] + $attributes);
                }
            }

            $definition->clearTag('doctrine.event_subscriber');
        }
    }
}
