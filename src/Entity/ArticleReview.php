<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Review\Model\Review;
use Sylius\Component\Review\Model\ReviewInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_article_review")
 */
class ArticleReview extends Review
{
    /**
     * ProductReview constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->status = ReviewInterface::STATUS_ACCEPTED;
    }
}
