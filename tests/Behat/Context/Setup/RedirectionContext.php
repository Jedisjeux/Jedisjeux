<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Context\Setup;

use App\Entity\Redirection;
use App\Fixture\Factory\RedirectionExampleFactory;
use Behat\Behat\Context\Context;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class RedirectionContext implements Context
{
    /**
     * @var RedirectionExampleFactory
     */
    private $redirectionFactory;

    /**
     * @var RepositoryInterface
     */
    private $redirectionRepository;

    /**
     * RedirectionContext constructor.
     *
     */
    public function __construct(RedirectionExampleFactory $redirectionFactory, RepositoryInterface $redirectionRepository)
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
