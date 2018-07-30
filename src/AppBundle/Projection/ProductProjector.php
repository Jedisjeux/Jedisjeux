<?php

declare(strict_types=1);

namespace AppBundle\Projection;

use AppBundle\Document\AppDocument;
use AppBundle\Entity\Product;
use AppBundle\Factory\Document\ProductDocumentFactory;
use ONGR\ElasticsearchBundle\Result\DocumentIterator;
use ONGR\ElasticsearchBundle\Service\Manager;
use ONGR\ElasticsearchBundle\Service\Repository;
use Sylius\Component\Product\Model\ProductInterface;
use AppBundle\Document\ProductDocument;
use AppBundle\Event\ProductCreated;
use AppBundle\Event\ProductDeleted;
use AppBundle\Event\ProductUpdated;

final class ProductProjector
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
     * @var ProductDocumentFactory
     */
    private $productDocumentFactory;

    /**
     * @param Manager $elasticsearchManager
     * @param ProductDocumentFactory $productDocumentFactory
     */
    public function __construct(
        Manager $elasticsearchManager,
        ProductDocumentFactory $productDocumentFactory
    ) {
        $this->elasticsearchManager = $elasticsearchManager;
        $this->appDocumentRepository = $elasticsearchManager->getRepository(AppDocument::class);
        $this->productDocumentFactory = $productDocumentFactory;
    }

    /**
     * @param ProductCreated $event
     *
     * @throws \ONGR\ElasticsearchBundle\Exception\BulkWithErrorsException
     * @throws \Exception
     */
    public function handleProductCreated(ProductCreated $event): void
    {
        $this->scheduleCreatingNewProductDocuments($event->product());

        $this->elasticsearchManager->commit();
    }

    /**
     * We create a new product documents with updated data and remove old once
     *
     * @param ProductUpdated $event
     *
     * @throws \ONGR\ElasticsearchBundle\Exception\BulkWithErrorsException
     * @throws \Exception
     */
    public function handleProductUpdated(ProductUpdated $event): void
    {
        $product = $event->product();

        $this->scheduleRemovingOldProductDocuments($product);
        $this->scheduleCreatingNewProductDocuments($product);

        $this->elasticsearchManager->commit();
    }

    /**
     * We remove deleted product
     *
     * @param ProductDeleted $event
     *
     * @throws \ONGR\ElasticsearchBundle\Exception\BulkWithErrorsException
     */
    public function handleProductDeleted(ProductDeleted $event): void
    {
        $product = $event->product();

        $this->scheduleRemovingOldProductDocuments($product);

        $this->elasticsearchManager->commit();
    }

    /**
     * @param ProductInterface|Product $product
     *
     * @throws \Exception
     */
    private function scheduleCreatingNewProductDocuments(ProductInterface $product): void
    {
        $this->elasticsearchManager->persist(
            $this->productDocumentFactory->create(
                $product
            )
        );
    }

    /**
     * @param ProductInterface $product
     */
    private function scheduleRemovingOldProductDocuments(ProductInterface $product): void
    {
        /** @var DocumentIterator|ProductDocument[] $currentProductDocuments */
        $currentProductDocuments = $this->appDocumentRepository->findBy([
            'type' => AppDocument::TYPE_PRODUCT,
            'code' => $product->getCode(),
        ]);

        foreach ($currentProductDocuments as $sameCodeProductDocument) {
            $this->elasticsearchManager->remove($sameCodeProductDocument);
        }
    }
}
