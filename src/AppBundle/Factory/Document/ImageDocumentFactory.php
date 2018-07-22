<?php

/**
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Factory\Document;

use AppBundle\Document\ImageDocument;
use AppBundle\Entity\AbstractImage;
use AppBundle\Entity\ProductVariantImage;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ImageDocumentFactory
{
    /**
     * @var string
     */
    private $imageDocumentClass;

    /**
     * @var CacheManager
     */
    private $cacheManager;

    /**
     * @param string $imageDocumentClass
     * @param CacheManager $cacheManager
     */
    public function __construct(string $imageDocumentClass, CacheManager $cacheManager)
    {
        $this->imageDocumentClass = $imageDocumentClass;
        $this->cacheManager = $cacheManager;
    }

    /**
     * @param AbstractImage $image
     *
     * @return ImageDocument
     */
    public function create(AbstractImage $image): ImageDocument
    {
        /** @var ImageDocument $imageDocument */
        $imageDocument = new $this->imageDocumentClass();
        $imageDocument->setPath($this->cacheManager->getBrowserPath($image->getWebPath(), 'thumbnail'));

        return $imageDocument;
    }
}
