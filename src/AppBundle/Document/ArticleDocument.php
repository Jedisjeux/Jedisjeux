<?php

/**
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Document;

use ONGR\ElasticsearchBundle\Annotation as ElasticSearch;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ElasticSearch\Object
 */
class ArticleDocument
{
    /**
     * @var int
     *
     * @ElasticSearch\Property(type="keyword")
     */
    private $id;

    /**
     * @var string
     *
     * @ElasticSearch\Property(type="keyword")
     */
    private $slug;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug)
    {
        $this->slug = $slug;
    }
}
