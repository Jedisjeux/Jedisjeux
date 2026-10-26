<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Fixture\Listener;

use App\Entity\Person;
use App\Updater\ProductCountByPersonUpdater;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Bundle\FixturesBundle\Listener\AbstractListener;
use Sylius\Bundle\FixturesBundle\Listener\AfterSuiteListenerInterface;
use Sylius\Bundle\FixturesBundle\Listener\SuiteEvent;

/**
 * Fixtures bypass resource events, so product counts by person must be computed once products are flushed.
 */
class ProductCountByPersonListener extends AbstractListener implements AfterSuiteListenerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ProductCountByPersonUpdater
     */
    private $updater;

    public function __construct(EntityManagerInterface $entityManager, ProductCountByPersonUpdater $updater)
    {
        $this->entityManager = $entityManager;
        $this->updater = $updater;
    }

    public function afterSuite(SuiteEvent $suiteEvent, array $options): void
    {
        $this->entityManager->clear();

        foreach ($this->entityManager->getRepository(Person::class)->findAll() as $person) {
            $this->updater->update($person);
        }

        $this->entityManager->flush();
        $this->entityManager->clear();
    }

    public function getName(): string
    {
        return 'product_count_by_person';
    }
}
