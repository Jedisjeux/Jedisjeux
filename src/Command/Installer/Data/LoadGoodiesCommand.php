<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Command\Installer\Data;

use App\Entity\CustomerInterface;
use App\Entity\ProductFile;
use App\Entity\ProductInterface;
use App\Entity\ProductVariantInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class LoadGoodiesCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected static $defaultName = 'app:load-goodies';

    /** @var string */
    private $csvPathName;

    /** @var ObjectManager */
    private $objectManager;

    /* @var Connection */
    private $connection;

    /* @var FactoryInterface */
    private $productFileFactory;

    /** @var RepositoryInterface */
    private $productFileRepository;

    /** @var RepositoryInterface */
    private $productVariantRepository;

    /** @var RepositoryInterface */
    private $customerRepository;

    /** @var string */
    private $uploadDestination;

    /**
     * @param string              $csvPathName
     * @param ObjectManager       $objectManager
     * @param Connection          $connection
     * @param FactoryInterface    $productFileFactory
     * @param RepositoryInterface $productFileRepository
     * @param RepositoryInterface $productVariantRepository
     * @param RepositoryInterface $customerRepository
     * @param string              $uploadDestination
     */
    public function __construct(
        string $csvPathName,
        ObjectManager $objectManager,
        Connection $connection,
        FactoryInterface $productFileFactory,
        RepositoryInterface $productFileRepository,
        RepositoryInterface $productVariantRepository,
        RepositoryInterface $customerRepository,
        string $uploadDestination
    ) {
        parent::__construct();

        $this->csvPathName = $csvPathName;
        $this->objectManager = $objectManager;
        $this->connection = $connection;
        $this->productFileFactory = $productFileFactory;
        $this->productFileRepository = $productFileRepository;
        $this->productVariantRepository = $productVariantRepository;
        $this->customerRepository = $customerRepository;
        $this->uploadDestination = $uploadDestination;

        $fileSystem = new Filesystem();
        $fileSystem->mkdir($this->uploadDestination);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $i = 0;
        $batchSize = 20;

        foreach ($this->getFiles() as $data) {
            // skip existing product file
            if (null !== $this->productFileRepository->findOneBy(['code' => $data['code']])) {
                continue;
            }

            $productFile = $this->createProductFile($data);

            if (null === $productFile) {
                continue;
            }

            $this->objectManager->persist($productFile);

            if (0 === ($i % $batchSize)) {
                $this->objectManager->flush();
                $this->objectManager->clear();
            }

            ++$i;
        }

        $this->objectManager->flush();
        $this->objectManager->clear();
    }

    private function getFiles(): \Iterator
    {
        foreach (file($this->csvPathName) as $key => $row) {
            $data = str_getcsv($row);

            list($url, $path, $file) = $data;

            if (empty($file)) {
                continue;
            }

            $databaseData = $this->getData($path);
            $databaseData['file'] = $file;

            yield $databaseData;
        }
    }

    /**
     * @return array
     *
     * @throws DBALException
     */
    private function getData(string $path): array
    {
        $sql = $this->connection->prepare(<<<EOM
SELECT concat('file-', id_goodies) as code,
       lien as path,
       id_game as game_id,
       user_id,
       description,
       dateadd as created_at
FROM jedisjeux.jdj_goodies  
WHERE lien = ?
EOM
        );

        $sql->bindValue(1, $path);
        $sql->execute();

        return $sql->fetch();

    }

    /**
     * @param array $data
     *
     * @return ProductFile|null
     *
     * @throws \Exception
     */
    private function createProductFile(array $data): ?ProductFile
    {
        /** @var ProductFile $productFile */
        $productFile = $this->productFileFactory->createNew();

        $fileName = basename($data['file']);

        try {
            $file = file_get_contents(sprintf('%s/%s', '/tmp', $fileName));
        } catch (\Exception $exception) {
            return null;
        }

        if (!$file) {
            return null;
        }

        $newPathName = sprintf('%s/%s', $this->uploadDestination, $fileName);
        file_put_contents($newPathName, $file);

        /** @var ProductVariantInterface $productVariant */
        $productVariant = $this->productVariantRepository->find($data['game_id']);
        /** @var ProductInterface $product */
        $product = $productVariant->getProduct();

        /** @var CustomerInterface $author */
        $author = $this->customerRepository->findOneBy(['code' => sprintf('user-%s', $data['user_id'])]);

        $productFile->setCode($data['code']);
        $productFile->setTitle($data['description']);
        $productFile->setStatus(ProductFile::STATUS_ACCEPTED);
        $productFile->setPath($fileName);
        $productFile->setProduct($product);
        $productFile->setAuthor($author);

        if (null !== $data['created_at'] && '0000-00-00 00:00:00' !== $data['created_at']) {
            $createdAt = \DateTime::createFromFormat('Y-m-d H:i:s', $data['created_at']);
            $productFile->setCreatedAt(false !== $createdAt ? $createdAt : null);
        }

        return $productFile;
    }
}
