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
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Connection;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class LoadProductFilesCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected static $defaultName = 'app:load-product-files';

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var FactoryInterface
     */
    private $productFileFactory;

    /**
     * @var RepositoryInterface
     */
    private $productRepository;

    /**
     * @var RepositoryInterface
     */
    private $customerRepository;

    /**
     * @var string
     */
    private $uploadDestination;

    /**
     * @param ObjectManager       $objectManager
     * @param Connection          $connection
     * @param FactoryInterface    $productFileFactory
     * @param RepositoryInterface $productRepository
     * @param RepositoryInterface $customerRepository
     * @param string              $uploadDestination
     */
    public function __construct(
        ObjectManager $objectManager,
        Connection $connection,
        FactoryInterface $productFileFactory,
        RepositoryInterface $productRepository,
        RepositoryInterface $customerRepository,
        string $uploadDestination
    ) {
        parent::__construct();

        $this->objectManager = $objectManager;
        $this->connection = $connection;
        $this->productFileFactory = $productFileFactory;
        $this->productRepository = $productRepository;
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
            $productFile = $this->createOrReplaceProductFile($data);

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

    /**
     * @return array
     */
    private function getFiles(): array
    {
        return $this->connection->fetchAll(<<<EOM
SELECT concat('file-', id_goodies) as code,
       lien as path,
       id_game as game_id,
       user_id,
       description,
       dateadd as created_at
FROM jedisjeux.jdj_goodies  
WHERE (
    lien like '%.pdf'
    OR lien like '%.pdf?'
    OR lien like '%.jpg'
    OR lien like 'goodies%'
)
EOM
);
    }

    /**
     * @param array $data
     *
     * @return ProductFile|null
     *
     * @throws \Exception
     */
    private function createOrReplaceProductFile(array $data): ?ProductFile
    {
        /** @var ProductFile $productFile */
        $productFile = $this->productFileFactory->createNew();

        $fileName = basename($data['path']);

        // relative path
        if (false !== strpos('goodies', $data['path'])) {
            // old files need to be downloaded on /tmp directory.
            $data['path'] = sprintf('%s/%s', '/tmp', $fileName);
        }

        try {
            $file = file_get_contents($data['path']);
        } catch (\Exception $exception) {
            return null;
        }

        if (!$file) {
            return null;
        }

        $newPathName = sprintf('%s/%s', $this->uploadDestination, $fileName);
        file_put_contents($newPathName, $file);

        /** @var ProductInterface $product */
        $product = $this->productRepository->find($data['game_id']);
        /** @var CustomerInterface $author */
        $author = $this->customerRepository->findOneBy(['code' => sprintf('user-%s', $data['user_id'])]);

        $productFile->setCode($data['code']);
        $productFile->setTitle($data['description']);
        $productFile->setStatus(ProductFile::STATUS_ACCEPTED);
        $productFile->setPath($fileName);
        $productFile->setProduct($product);
        $productFile->setAuthor($author);

        return $productFile;
    }
}
