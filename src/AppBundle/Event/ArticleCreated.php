<?php

declare(strict_types=1);

namespace AppBundle\Event;

use AppBundle\Entity\Article;

final class ArticleCreated
{
    /**
     * @var Article
     */
    private $article;

    /**
     * @param Article $article
     */
    private function __construct(Article $article)
    {
        $this->article = $article;
    }

    /**
     * @param Article $article
     *
     * @return self
     */
    public static function occur(Article $article)
    {
        return new self($article);
    }

    /**
     * @return Article
     */
    public function article(): Article
    {
        return $this->article;
    }
}
