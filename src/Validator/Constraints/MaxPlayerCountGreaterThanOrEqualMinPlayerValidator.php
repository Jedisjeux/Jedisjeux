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

use App\Entity\Product;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ValidatorException;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class MaxPlayerCountGreaterThanOrEqualMinPlayerValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($product, Constraint $constraint)
    {
        if (!$product instanceof Product) {
            throw new ValidatorException(sprintf("product should be an instance of %s", Product::class));
        }

        if (null === $product->getMinPlayerCount()) {
            return;
        }

        if (null === $product->getMaxPlayerCount()) {
            return;
        }

        if ($product->getMinPlayerCount() > $product->getMaxPlayerCount()) {
            $this->context->buildViolation($constraint->message)
                ->atPath('minPlayerCount')
                ->addViolation();
        }
    }
}
