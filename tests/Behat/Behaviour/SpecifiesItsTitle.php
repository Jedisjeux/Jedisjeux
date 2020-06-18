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

namespace App\Tests\Behat\Behaviour;

use Behat\Mink\Exception\ElementNotFoundException;

trait SpecifiesItsTitle
{
    use DocumentAccessor;

    /**
     * @param string|null $title
     *
     * @throws ElementNotFoundException
     */
    public function specifyTitle(?string $title): void
    {
        $this->getDocument()->fillField('Title', $title);
    }
}
