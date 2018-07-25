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
use AppBundle\Document\TopicDocument;
use AppBundle\Entity\Topic;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class TopicDocumentFactory
{
    /**
     * @var string
     */
    private $appDocumentClass;

    /**
     * @var string
     */
    private $topicDocumentClass;

    /**
     * @var ImageDocumentFactory
     */
    private $imageDocumentFactory;

    /**
     * @param string $appDocumentClass
     * @param string $topicDocumentClass
     * @param ImageDocumentFactory $imageDocumentFactory
     */
    public function __construct(
        string $appDocumentClass,
        string $topicDocumentClass,
        ImageDocumentFactory $imageDocumentFactory
    ) {
        $this->appDocumentClass = $appDocumentClass;
        $this->topicDocumentClass = $topicDocumentClass;
        $this->imageDocumentFactory = $imageDocumentFactory;
    }

    /**
     * @param Topic $topic
     *
     * @return AppDocument
     */
    public function create(Topic $topic): AppDocument
    {
        /** @var AppDocument $appDocument */
        $appDocument = new $this->appDocumentClass();
        $appDocument->setType(AppDocument::TYPE_TOPIC);
        $appDocument->setCode($topic->getCode());
        $appDocument->setName($topic->getTitle());
        $appDocument->setCreatedAt($topic->getCreatedAt());

        /** @var TopicDocument $topicDocument */
        $topicDocument = new $this->topicDocumentClass();
        $topicDocument->setId($topic->getId());

        if (null !== $mainImage = $topic->getAuthor()->getAvatar()) {
            $imageDocument = $this->imageDocumentFactory->create($mainImage);
            $appDocument->setImage($imageDocument);
        }

        $appDocument->setTopic($topicDocument);

        return $appDocument;
    }
}
