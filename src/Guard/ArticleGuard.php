<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
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
