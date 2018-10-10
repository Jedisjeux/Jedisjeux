<?php

/**
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Command;

use App\Entity\Product;
use App\Entity\Dealer;
use App\Entity\DealerPrice;
use App\Repository\ProductRepository;
use Behat\Transliterator\Transliterator;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Sylius\Component\Resource\Factory\Factory;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ImportDealerPricesCommand extends ContainerAwareCommand
{
    const BATCH_SIZE = 1;

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
            ->setName('app:dealer-prices:import')
            ->addArgument('dealer', InputArgument::REQUIRED, 'dealer')
            ->addOption('filename', null, InputOption::VALUE_REQUIRED, 'filename to import')
            ->addOption('remove-first-line', null, InputOption::VALUE_REQUIRED, null, false)
            ->addOption('delimiter', null, InputOption::VALUE_REQUIRED, null, ';')
            ->addOption('utf8', null, InputOption::VALUE_REQUIRED, null, true)
            ->setDescription('Import prices from a dealer')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command import prices from a dealer.
EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getArgument('dealer')) {
            $dealer = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please enter a dealer code:',
                function ($dealer) {
                    if (empty($dealer)) {
                        throw new \Exception('Dealer can not be empty');
                    }

                    return $dealer;
                }
            );

            $input->setArgument('dealer', $dealer);
        }

        if (!$input->getOption('filename')) {
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

            $input->setOption('filename', $file);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf('<comment>%s</comment>', $this->getDescription()));

        $dealer = $this->ensureDealerAlreadyExists();
        $i = 0;

        foreach ($this->getCsvData() as $data) {
            $output->writeln(sprintf('Import of <comment>%s</comment> product', $data['product_name']));

            $dealerPrice = $this->createOrReplaceDealerPrice($data, $dealer);

            if (!$this->getManager()->contains($dealerPrice)) {
                $this->getManager()->persist($dealerPrice);
            }

            if (($i % self::BATCH_SIZE) === 0) {
                $this->getManager()->flush(); // Executes all updates.
                $this->getManager()->clear(); // Detaches all objects from Doctrine!

                // dealer is detached from doctrine, so we have to find it again
                $dealer = $this->ensureDealerAlreadyExists();
            }

            ++$i;
        }

        $this->getManager()->flush();
        $this->removeOutOfCatalogDealerPrices($dealer);
        $this->getManager()->clear();
    }

    /**
     * @return Dealer
     *
     * @throws \Exception
     */
    protected function ensureDealerAlreadyExists()
    {
        $code = $this->input->getArgument('dealer');
        /** @var Dealer $dealer */
        $dealer = $this->getDealerRepository()->findOneBy(['code' => $code]);

        if (null === $dealer) {
            throw new \Exception(sprintf('Dealer with code %s does not exist', $code));
        }

        return $dealer;
    }

    /**
     * @param array $data
     * @param Dealer $dealer
     *
     * @return DealerPrice
     */
    protected function createOrReplaceDealerPrice(array $data, Dealer $dealer)
    {
        /** @var DealerPrice $dealerPrice */
        $dealerPrice = $this->getRepository()->findOneBy(['url' => $data['url']]);

        if (null === $dealerPrice) {
            $dealerPrice = $this->getFactory()->createNew();
        }

        /** @var Product $product */
        $product = $this->findOneProductByData($data);

        if (null !== $product) {
            $dealerPrice
                ->setProduct($product);
        }

        $price = $this->formatPrice($data['price']);

        $dealerPrice->setDealer($dealer);
        $dealerPrice->setUrl($data['url']);
        $dealerPrice->setName(preg_replace('/[[:^print:]]/', '', $data['product_name']));
        $dealerPrice->setPrice($price);
        $dealerPrice->setBarcode($data['barcode']);
        $dealerPrice->setStatus($data['status']);
        $dealerPrice->setUpdatedAt(new \DateTime()); // ensure doctrine will update data when no data has changed

        return $dealerPrice;
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
        $filename = $this->input->getOption('filename');
        $data = [];

        foreach (file($filename) as $key => $row) {
            if ($this->input->getOption('remove-first-line') and $key === 0) {
                continue;
            }

            $rowData = str_getcsv($row, $this->input->getOption('delimiter'));
            $rowData = array_map('trim', $rowData);

            if (!$this->input->getOption('utf8')) {
                $rowData = array_map('utf8_encode', $rowData);
            }

            $error = false;

            $status = null;

            switch ($rowData[3]) {
                case 'Dispo':
                case 'Disponible':
                case 'disponible':
                    $status = DealerPrice::STATUS_AVAILABLE;
                    break;
                case 'En cours de réappro':
                case 'Rupture':
                case 'indisponible':
                    $status = DealerPrice::STATUS_OUT_OF_STOCK;
                    break;
                case 'Pr?commande':
                case preg_replace('/[^a-z]/', '', $rowData[3]) === 'prcommande':
                    $status = DealerPrice::STATUS_PRE_ORDER;
                    break;
                default:
                    $this->output->writeln(sprintf('<error>Status with code %s does not exist on %s</error>', $rowData[3], $rowData[0]));
                    $error = true;
            }

            if ($error) {
                continue;
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
        $product = $this->getProductRepository()->findOneByBarcode($data['barcode']);

        if (null !== $product) {
            return $product;
        }

        $slug = Transliterator::transliterate($data['product_name']);
        $product = $this->getProductRepository()->findOneBySlug($slug);

        return $product;
    }

    /**
     * @return integer nbRows deleted
     */
    protected function removeOutOfCatalogDealerPrices(Dealer $dealer)
    {
        $query = $this->getManager()->createQuery('delete from App:DealerPrice o where o.dealer = :dealer and o.updatedAt < :today');

        return $query->execute([
            'dealer' => $dealer,
            'today' => (new \DateTime('today'))->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * @return ProductRepository|object
     */
    protected function getProductRepository()
    {
        return $this->getContainer()->get('sylius.repository.product');
    }

    /**
     * @return EntityRepository|object
     */
    protected function getDealerRepository()
    {
        return $this->getContainer()->get('app.repository.dealer');
    }

    /**
     * @return Factory|object
     */
    protected function getFactory()
    {
        return $this->getContainer()->get('app.factory.dealer_price');
    }

    /**
     * @return EntityRepository|object
     */
    protected function getRepository()
    {
        return $this->getContainer()->get('app.repository.dealer_price');
    }

    /**
     * @return EntityManager|object
     */
    protected function getManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }
}
