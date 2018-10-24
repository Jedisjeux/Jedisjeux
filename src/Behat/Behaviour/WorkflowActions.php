<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Behaviour;


use Behat\Mink\Element\DocumentElement;
use Behat\Mink\Exception\ElementNotFoundException;

trait WorkflowActions
{
    /**
     * @return DocumentElement
     */
    abstract protected function getDocument();

    /**
     * @throws ElementNotFoundException
     */
    public function askForTranslation(): void
    {
        $this->getDocument()->pressButton('Ask for translation');
    }

    /**
     * @throws ElementNotFoundException
     */
    public function askForReview(): void
    {
        $this->getDocument()->pressButton('Ask for review');
    }

    /**
     * @throws ElementNotFoundException
     */
    public function askForPublication(): void
    {
        $this->getDocument()->pressButton('Ask for publication');
    }

    /**
     * @throws ElementNotFoundException
     */
    public function publish(): void
    {
        $this->getDocument()->pressButton('Publish');
    }
}
