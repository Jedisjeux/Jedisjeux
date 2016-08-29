<?php

/**
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Command;

use AppBundle\Entity\Product;
use AppBundle\Entity\Shopper;
use AppBundle\Entity\ShopperPrice;
use AppBundle\Repository\ProductRepository;
use Behat\Transliterator\Transliterator;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Elastica\Query;
use FOS\ElasticaBundle\Finder\TransformedFinder;
use Pagerfanta\Pagerfanta;
use Sylius\Component\Resource\Factory\Factory;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ImportShopperPricesCommand extends ContainerAwareCommand
{
    const BATCH_SIZE = 20;

    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

        $this->input = $input;
        $this->output = $output;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:shopper-prices:import')
            ->addArgument('shopper', InputArgument::REQUIRED, 'shopper')
            ->addArgument('file', InputArgument::REQUIRED, 'file to import')
            ->setDescription('Import prices from a shopper')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command import prices from a shopper.
EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getArgument('shopper')) {
            $shopper = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please enter a shopper code:',
                function ($shopper) {
                    if (empty($shopper)) {
                        throw new \Exception('Shopper can not be empty');
                    }

                    return $shopper;
                }
            );

            $input->setArgument('shopper', $shopper);
        }

        if (!$input->getArgument('file')) {
            $file = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please enter a file path:',
                function ($file) {
                    if (empty($file)) {
                        throw new \Exception('File can not be empty');
                    }

                    return $file;
                }
            );

            $input->setArgument('file', $file);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf('<comment>%s</comment>', $this->getDescription()));

        $shopper = $this->ensureShopperAlreadyExists();
        $i = 0;

        foreach ($this->getCsvData() as $data) {
            $output->writeln(sprintf('Import of <comment>%s</comment> product', $data['product_name']));

            $shopperPrice = $this->createOrReplaceShopperPrice($data, $shopper);

            if (!$this->getManager()->contains($shopperPrice)) {
                $this->getManager()->persist($shopperPrice);
            }

            if (($i % self::BATCH_SIZE) === 0) {
                $this->getManager()->flush(); // Executes all updates.
                $this->getManager()->clear(); // Detaches all objects from Doctrine!

                // shopper is detached from doctrine, so we have to find it again
                $shopper = $this->ensureShopperAlreadyExists();
            }

            ++$i;
        }

        $this->getManager()->flush();
        $this->getManager()->clear();
    }

    /**
     * @return Shopper
     *
     * @throws \Exception
     */
    protected function ensureShopperAlreadyExists()
    {
        $code = $this->input->getArgument('shopper');
        /** @var Shopper $shopper */
        $shopper = $this->getShopperRepository()->findOneBy(['code' => $code]);

        if (null === $shopper) {
            throw new \Exception(sprintf('Shopper with code %s does not exist', $code));
        }

        return $shopper;
    }

    /**
     * @param array $data
     * @param Shopper $shopper
     *
     * @return ShopperPrice
     */
    protected function createOrReplaceShopperPrice(array $data, Shopper $shopper)
    {
        /** @var ShopperPrice $shopperPrice */
        $shopperPrice = $this->getRepository()->findOneBy(['url' => $data['url']]);

        if (null === $shopperPrice) {
            $shopperPrice = $this->getFactory()->createNew();
        }

        if (null === $shopperPrice->getProduct()) {
            /** @var Product $product */
            $product = $this->findOneProductByData($data);

            if (null !== $product) {
                $shopperPrice
                    ->setProduct($product);
            }
        }

        $price = $this->formatPrice($data['price']);

        $shopperPrice
            ->setShopper($shopper)
            ->setUrl($data['url'])
            ->setName($data['product_name'])
            ->setPrice($price)
            ->setStatus($data['status'])
            ->setUpdatedAt(new \DateTime()); // ensure doctrine will update data when no data has changed

        return $shopperPrice;
    }

    protected function formatPrice($price)
    {
        // replace comma by dot as decimal separator
        $price = str_replace(',', '.', $price);

        // float to integer
        $price *= 100;

        return $price;
    }

    /**
     * @return array
     *
     * @throws \Exception
     */
    protected function getCsvData()
    {
        $file = $this->input->getArgument('file');
        $data = [];

        foreach (file($file) as $row) {
            $rowData = str_getcsv($row, ';');

            switch ($rowData[3]) {
                case 'disponible':
                    $status = ShopperPrice::STATUS_AVAILABLE;
                    break;
                default:
                    throw new \Exception(sprintf('Shopper with code %s does not exist', $code));
            }

            $data[] = [
                'url' => $rowData[0],
                'product_name' => $rowData[1],
                'price' => $rowData[2],
                'status' => $status,
                'publisher' => isset($rowData[4]) ? $rowData[4] : null,
                'barcode' => isset($rowData[5]) ? $rowData[5] : null,
            ];
        }

        return $data;
    }

    /**
     * @param array $data
     *
     * @return Product|null
     */
    protected function findOneProductByData(array $data)
    {
        $query = Transliterator::urlize($data['product_name']);
        $finder = $this->getProductFinder();

        $searchQuery = new Query\QueryString();
        $searchQuery->setParam('query', $query);

        $searchQuery->setDefaultOperator('AND');

        // execute a request of type "fields", with all theses following columns
        $searchQuery->setParam('fields', array(
            'slug',
            'name',
        ));

        /** @var Pagerfanta $userPaginator */
        $paginator = $finder->findPaginated($searchQuery);

        if (1 === $paginator->getNbResults()) {
            return $paginator->getIterator()->current();
        }

        return null;
    }

    /**
     * @return TransformedFinder
     */
    protected function getProductFinder()
    {
        return $this->getContainer()->get('fos_elastica.finder.jedisjeux.product');
    }

    /**
     * @return ProductRepository
     */
    protected function getProductRepository()
    {
        return $this->getContainer()->get('sylius.repository.product');
    }

    /**
     * @return EntityRepository
     */
    protected function getShopperRepository()
    {
        return $this->getContainer()->get('app.repository.shopper');
    }

    /**
     * @return Factory
     */
    protected function getFactory()
    {
        return $this->getContainer()->get('app.factory.shopper_price');
    }

    /**
     * @return EntityRepository
     */
    protected function getRepository()
    {
        return $this->getContainer()->get('app.repository.shopper_price');
    }

    /**
     * @return EntityManager
     */
    protected function getManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }
}
