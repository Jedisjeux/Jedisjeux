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
class ImageDocument
{
    /**
     * @var string
     *
     * @ElasticSearch\Property(type="keyword")
     */
    private $path;

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
    }
}
