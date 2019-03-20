<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Guard;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ArticleGuard
{
    /**
     * @var AuthorizationCheckerInterface
     */
    protected $authorizationChecker;

    /**
     * ArticleGuard constructor.
     *
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @return bool
     */
    public function canAskForPublication()
    {
        return $this->authorizationChecker->isGranted('ROLE_REVIEWER');
    }

    /**
     * @return bool
     */
    public function canPublish()
    {
        return $this->authorizationChecker->isGranted('ROLE_PUBLISHER');
    }
}
