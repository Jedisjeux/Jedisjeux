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
use AppBundle\Document\ArticleDocument;
use AppBundle\Entity\Article;
use AppBundle\Entity\Topic;
use Ramsey\Uuid\Uuid;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ArticleDocumentFactory
{
    /**
     * @var string
     */
    private $appDocumentClass;

    /**
     * @var string
     */
    private $articleDocumentClass;

    /**
     * @var ImageDocumentFactory
     */
    private $imageDocumentFactory;

    /**
     * @param string $appDocumentClass
     * @param string $articleDocumentClass
     * @param ImageDocumentFactory $imageDocumentFactory
     */
    public function __construct(
        string $appDocumentClass,
        string $articleDocumentClass,
        ImageDocumentFactory $imageDocumentFactory
    ) {
        $this->appDocumentClass = $appDocumentClass;
        $this->articleDocumentClass = $articleDocumentClass;
        $this->imageDocumentFactory = $imageDocumentFactory;
    }

    /**
     * @param Article $article
     *
     * @return AppDocument
     *
     * @throws \Exception
     */
    public function create(Article $article): AppDocument
    {
        /** @var AppDocument $appDocument */
        $appDocument = new $this->appDocumentClass();
        $appDocument->setUuid(Uuid::uuid4()->toString());
        $appDocument->setType(AppDocument::TYPE_ARTICLE);
        $appDocument->setCode($article->getCode());
        $appDocument->setName($article->getTitle());
        $appDocument->setCreatedAt($article->getCreatedAt());

        /** @var ArticleDocument $articleDocument */
        $articleDocument = new $this->articleDocumentClass();
        $articleDocument->setId($article->getId());
        $articleDocument->setSlug($article->getSlug());

        $mainImage = $article->getMainImage();

        if (null !== $mainImage && null !== $mainImage->getPath()) {
            $imageDocument = $this->imageDocumentFactory->create($mainImage);
            $appDocument->setImage($imageDocument);
        }

        $appDocument->setArticle($articleDocument);

        return $appDocument;
    }
}
