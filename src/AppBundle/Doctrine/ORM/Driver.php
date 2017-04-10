<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Doctrine\ORM;

use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Grid\Data\DriverInterface;
use Sylius\Component\Grid\Parameters;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class Driver implements DriverInterface
{
    const NAME = 'doctrine/orm';

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getDataSource(array $configuration, Parameters $parameters)
    {
        if (!array_key_exists('class', $configuration)) {
            throw new \InvalidArgumentException('"class" must be configured.');
        }

        $repository = $this->entityManager->getRepository($configuration['class']);

        if (isset($configuration['repository']['method'])) {
            $method = $configuration['repository']['method'];
            $arguments = isset($configuration['repository']['arguments']) ? array_values($configuration['repository']['arguments']) : [];

            $queryBuilder = $repository->$method(...$arguments);
        } else {
            $queryBuilder = $repository->createQueryBuilder('o');
        }

        return new DataSource($queryBuilder);
    }
}
