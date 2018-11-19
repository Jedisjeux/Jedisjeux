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

namespace App\Behat\Behaviour;

use Behat\Mink\Element\DocumentElement;

trait DocumentAccessor
{
    /**
     * @return DocumentElement
     */
    abstract protected function getDocument();
}
