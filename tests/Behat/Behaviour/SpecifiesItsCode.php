<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Tests\Behat\Behaviour;

use Behat\Mink\Exception\ElementNotFoundException;

trait SpecifiesItsCode
{
    use DocumentAccessor;

    /**
     *
     * @throws ElementNotFoundException
     */
    public function specifyCode(?string $code): void
    {
        $this->getDocument()->fillField('Code', $code);
    }
}
