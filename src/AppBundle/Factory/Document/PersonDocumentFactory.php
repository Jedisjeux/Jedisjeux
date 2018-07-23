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
use AppBundle\Document\PersonDocument;
use AppBundle\Entity\Article;
use AppBundle\Entity\Person;
use AppBundle\Entity\Topic;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class PersonDocumentFactory
{
    /**
     * @var string
     */
    private $appDocumentClass;

    /**
     * @var string
     */
    private $personDocumentClass;

    /**
     * @var ImageDocumentFactory
     */
    private $imageDocumentFactory;

    /**
     * @param string $appDocumentClass
     * @param string $personDocumentClass
     * @param ImageDocumentFactory $imageDocumentFactory
     */
    public function __construct(
        string $appDocumentClass,
        string $personDocumentClass,
        ImageDocumentFactory $imageDocumentFactory
    ) {
        $this->appDocumentClass = $appDocumentClass;
        $this->personDocumentClass = $personDocumentClass;
        $this->imageDocumentFactory = $imageDocumentFactory;
    }

    /**
     * @param Person $person
     *
     * @return AppDocument
     */
    public function create(Person $person): AppDocument
    {
        /** @var AppDocument $appDocument */
        $appDocument = new $this->appDocumentClass();
        $appDocument->setType(AppDocument::TYPE_PERSON);
        $appDocument->setName($person->getFullName());
        $appDocument->setCreatedAt($person->getCreatedAt());

        /** @var PersonDocument $personDocument */
        $personDocument = new $this->personDocumentClass();
        $personDocument->setId($person->getId());
        $personDocument->setSlug($person->getSlug());

        if (null !== $mainImage = $person->getFirstImage()) {
            $imageDocument = $this->imageDocumentFactory->create($mainImage);
            $appDocument->setImage($imageDocument);
        }

        $appDocument->setPerson($personDocument);

        return $appDocument;
    }
}
