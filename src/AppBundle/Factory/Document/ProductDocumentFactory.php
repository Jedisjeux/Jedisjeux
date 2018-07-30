<?php

declare(strict_types=1);

/**
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Factory\Document;

use AppBundle\Document\AppDocument;
use AppBundle\Document\ProductDocument;
use AppBundle\Entity\Product;
use Ramsey\Uuid\Uuid;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductDocumentFactory
{
    /**
     * @var string
     */
    private $appDocumentClass;

    /**
     * @var string
     */
    private $productDocumentClass;

    /**
     * @var ImageDocumentFactory
     */
    private $imageDocumentFactory;

    /**
     * @param string $appDocumentClass
     * @param string $productDocumentClass
     * @param ImageDocumentFactory $imageDocumentFactory
     */
    public function __construct(
        string $appDocumentClass,
        string $productDocumentClass,
        ImageDocumentFactory $imageDocumentFactory
    ) {
        $this->appDocumentClass = $appDocumentClass;
        $this->productDocumentClass = $productDocumentClass;
        $this->imageDocumentFactory = $imageDocumentFactory;
    }

    /**
     * @param Product $product
     *
     * @return AppDocument
     *
     * @throws \Exception
     */
    public function create(Product $product): AppDocument
    {
        /** @var AppDocument $appDocument */
        $appDocument = new $this->appDocumentClass();
        $appDocument->setUuid(Uuid::uuid4()->toString());
        $appDocument->setType(AppDocument::TYPE_PRODUCT);
        $appDocument->setCode($product->getCode());
        $appDocument->setName($product->getName());
        $appDocument->setCreatedAt($product->getCreatedAt());

        /** @var ProductDocument $productDocument */
        $productDocument = new $this->productDocumentClass();
        $productDocument->setId($product->getId());
        $productDocument->setSlug($product->getSlug());

        if (null !== $mainImage = $product->getMainImage()) {
            $imageDocument = $this->imageDocumentFactory->create($mainImage);
            $appDocument->setImage($imageDocument);
        }

        $appDocument->setProduct($productDocument);

        return $appDocument;
    }
}
