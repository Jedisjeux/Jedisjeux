<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\ExpressionLanguage;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

/**
 * Gives is_granted() to the expressions of the #[AsResource] operations.
 */
#[AutoconfigureTag('sylius.repository_providers')]
#[AutoconfigureTag('sylius.resource_factory_providers')]
#[AutoconfigureTag('sylius.metadata_providers')]
final class SecurityExpressionFunctionProvider implements ExpressionFunctionProviderInterface
{
    public function __construct(
        private readonly Security $security,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new ExpressionFunction(
                'is_granted',
                static fn (string $attribute): string => sprintf('$this->security->isGranted(%s)', $attribute),
                fn (array $arguments, string $attribute): bool => $this->security->isGranted($attribute),
            ),
        ];
    }
}
