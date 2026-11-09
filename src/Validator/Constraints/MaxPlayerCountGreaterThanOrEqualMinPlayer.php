<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class MaxPlayerCountGreaterThanOrEqualMinPlayer extends Constraint
{
    public $message = 'app.product.player_count.min_value_should_not_be_greater_than_max_value';

    /**
     * {@inheritdoc}
     */
    public function getTargets(): string|array
    {
        return self::CLASS_CONSTRAINT;
    }

    /**
     * {@inheritdoc}
     */
    public function validateBy(): string
    {
        return MaxPlayerCountGreaterThanOrEqualMinPlayerValidator::class;
    }
}
