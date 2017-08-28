<?php

/**
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Behat\Context\Transform;

use AppBundle\Entity\Redirection;
use Behat\Behat\Context\Context;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class RedirectionContext implements Context
{
    /**
     * @var RepositoryInterface
     */
    protected $redirectionRepository;

    /**
     * RedirectionContext constructor.
     *
     * @param RepositoryInterface $redirectionRepository
     */
    public function __construct(RepositoryInterface $redirectionRepository)
    {
        $this->redirectionRepository = $redirectionRepository;
    }

    /**
     * @Transform /^redirection "([^"]+)"$/
     * @Transform :redirection
     *
     * @param string $source
     *
     * @return Redirection
     */
    public function getRedirectionBySource($source)
    {
        /** @var Redirection $redirection */
        $redirection = $this->redirectionRepository->findOneBy(['source' => $source]);

        Assert::notNull(
            $redirection,
            sprintf('Redirection with source "%s" does not exist', $source)
        );

        return $redirection;
    }
}
