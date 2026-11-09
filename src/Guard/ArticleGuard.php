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
     * ArticleGuard constructor.
     */
    public function __construct(protected AuthorizationCheckerInterface $authorizationChecker)
    {
    }

    /**
     * @return bool
     */
    public function canAskForPublication(): bool
    {
        return $this->authorizationChecker->isGranted('ROLE_REVIEWER');
    }

    /**
     * @return bool
     */
    public function canPublish(): bool
    {
        return $this->authorizationChecker->isGranted('ROLE_PUBLISHER');
    }
}
