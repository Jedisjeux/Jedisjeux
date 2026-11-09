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

namespace App\Tests\Behat\Context\Setup;

use App\Entity\Person;
use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

final class PersonZoneContext implements Context
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @Given /^(this person) is from ("[^"]+" zone)$/
     */
    public function thisPersonIsFromZone(Person $person, TaxonInterface $zone): void
    {
        $person->setZone($zone);

        $this->entityManager->flush();
    }
}
