<?php

/**
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Context\Setup;

use App\Entity\Dealer;
use App\Entity\Redirection;
use App\Fixture\Factory\ExampleFactoryInterface;
use Behat\Behat\Context\Context;
use Sylius\Component\Resource\Repository\RepositoryInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class RedirectionContext implements Context
{
    /**
     * @var ExampleFactoryInterface
     */
    private $redirectionFactory;

    /**
     * @var RepositoryInterface
     */
    private $redirectionRepository;

    /**
     * RedirectionContext constructor.
     *
     * @param ExampleFactoryInterface $redirectionFactory
     * @param RepositoryInterface $redirectionRepository
     */
    public function __construct(ExampleFactoryInterface $redirectionFactory, RepositoryInterface $redirectionRepository)
    {
        $this->redirectionFactory = $redirectionFactory;
        $this->redirectionRepository = $redirectionRepository;
    }

    /**
     * @Given there is redirection :source
     *
     * @param string $source
     */
    public function thereIsRedirection($source)
    {
        /** @var Redirection $redirection */
        $redirection = $this->redirectionFactory->create([
            'source' => $source,
        ]);

        $this->redirectionRepository->add($redirection);
    }
}
