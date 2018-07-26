<?php

declare(strict_types=1);

namespace AppBundle\Projection;

use AppBundle\Document\AppDocument;
use AppBundle\Document\PersonDocument;
use AppBundle\Entity\Person;
use AppBundle\Event\PersonCreated;
use AppBundle\Event\PersonDeleted;
use AppBundle\Event\PersonUpdated;
use AppBundle\Factory\Document\PersonDocumentFactory;
use ONGR\ElasticsearchBundle\Result\DocumentIterator;
use ONGR\ElasticsearchBundle\Service\Manager;
use ONGR\ElasticsearchBundle\Service\Repository;

final class PersonProjector
{
    /**
     * @var Manager
     */
    private $elasticsearchManager;

    /**
     * @var Repository
     */
    private $appDocumentRepository;

    /**
     * @var PersonDocumentFactory
     */
    private $personDocumentFactory;

    /**
     * @param Manager $elasticsearchManager
     * @param PersonDocumentFactory $personDocumentFactory
     */
    public function __construct(
        Manager $elasticsearchManager,
        PersonDocumentFactory $personDocumentFactory
    ) {
        $this->elasticsearchManager = $elasticsearchManager;
        $this->appDocumentRepository = $elasticsearchManager->getRepository(AppDocument::class);
        $this->personDocumentFactory = $personDocumentFactory;
    }

    /**
     * @param PersonCreated $event
     *
     * @throws \ONGR\ElasticsearchBundle\Exception\BulkWithErrorsException
     * @throws \Exception
     */
    public function handlePersonCreated(PersonCreated $event): void
    {
        $this->scheduleCreatingNewPersonDocuments($event->person());

        $this->elasticsearchManager->commit();
    }

    /**
     * We create a new person documents with updated data and remove old once
     *
     * @param PersonUpdated $event
     *
     * @throws \ONGR\ElasticsearchBundle\Exception\BulkWithErrorsException
     * @throws \Exception
     */
    public function handlePersonUpdated(PersonUpdated $event): void
    {
        $person = $event->person();

        $this->scheduleRemovingOldPersonDocuments($person);
        $this->scheduleCreatingNewPersonDocuments($person);

        $this->elasticsearchManager->commit();
    }

    /**
     * We remove deleted person
     *
     * @param PersonDeleted $event
     *
     * @throws \ONGR\ElasticsearchBundle\Exception\BulkWithErrorsException
     */
    public function handlePersonDeleted(PersonDeleted $event): void
    {
        $person = $event->person();

        $this->scheduleRemovingOldPersonDocuments($person);

        $this->elasticsearchManager->commit();
    }

    /**
     * @param Person $person
     *
     * @throws \Exception
     */
    private function scheduleCreatingNewPersonDocuments(Person $person): void
    {
        $this->elasticsearchManager->persist(
            $this->personDocumentFactory->create(
                $person
            )
        );
    }

    /**
     * @param Person $person
     */
    private function scheduleRemovingOldPersonDocuments(Person $person): void
    {
        /** @var DocumentIterator|PersonDocument[] $currentPersonDocuments */
        $currentPersonDocuments = $this->appDocumentRepository->findBy([
            'type' => AppDocument::TYPE_PERSON,
            'code' => $person->getCode(),
        ]);

        foreach ($currentPersonDocuments as $sameCodePersonDocument) {
            $this->elasticsearchManager->remove($sameCodePersonDocument);
        }
    }
}
