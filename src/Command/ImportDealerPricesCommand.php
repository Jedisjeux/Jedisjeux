<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Command;

use App\Command\Helper\ProgressBarCreator;
use App\Entity\Dealer;
use App\Entity\DealerPrice;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Behat\Transliterator\Transliterator;
use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class ImportDealerPricesCommand extends Command
{
    private const BATCH_SIZE = 1;

    /** @var FactoryInterface */
    private $dealerPriceFactory;

    /** @var RepositoryInterface */
    private $dealerRepository;

    /** @var RepositoryInterface */
    private $dealerPriceRepository;

    /** @var RepositoryInterface|ProductRepository */
    private $productRepository;

    /** @var ObjectManager */
    private $manager;

    /** @var string */
    private $locale;

    /** @var InputInterface */
    private $input;

    /** @var OutputInterface */
    private $output;

    /** @var ProgressBarCreator */
    private $progressBarCreator;

    public function __construct(
        FactoryInterface $dealerPriceFactory,
        RepositoryInterface $dealerRepository,
        RepositoryInterface $dealerPriceRepository,
        RepositoryInterface $productRepository,
        ObjectManager $manager,
        string $locale,
        ProgressBarCreator $progressBarCreator
    ) {
        $this->dealerPriceFactory = $dealerPriceFactory;
        $this->dealerRepository = $dealerRepository;
        $this->dealerPriceRepository = $dealerPriceRepository;
        $this->productRepository = $productRepository;
        $this->manager = $manager;
        $this->locale = $locale;
        $this->progressBarCreator = $progressBarCreator;

        parent::__construct();
    }

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
            ->addOption('remove-first-line', null, InputOption::VALUE_REQUIRED, 'remove first line if it contains an heading row', false)
            ->addOption('delimiter', null, InputOption::VALUE_REQUIRED, 'csv delimiter', ';')
            ->addOption('utf8', null, InputOption::VALUE_REQUIRED, 'file uses utf8 charset', true)
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
            /** @var QuestionHelper $helper */
            $helper = $this->getHelper('question');
            $question = new Question('Please enter a dealer code:');
            $dealer = $helper->ask(
                $input,
                $output,
                $question
            );

            $input->setArgument('dealer', $dealer);
        }

        if (!$input->getOption('filename')) {
            /** @var QuestionHelper $helper */
            $helper = $this->getHelper('question');
            $question = new Question('Please enter a file path:');
            $file = $helper->ask(
                $input,
                $output,
                $question
            );

            $input->setOption('filename', $file);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dealer = $this->ensureDealerAlreadyExists();
        $i = 0;

        $csvData = $this->getCsvData();
        $progress = $this->progressBarCreator->create($output, count($csvData));
        $matchingCount = 0;

        foreach ($csvData as $data) {
            $dealerPrice = $this->createOrReplaceDealerPrice($data, $dealer);

            if (null !== $dealerPrice->getProduct()) {
                ++$matchingCount;
            }

            if (!$this->manager->contains($dealerPrice)) {
                $this->manager->persist($dealerPrice);
            }

            if (0 === ($i % self::BATCH_SIZE)) {
                $this->manager->flush(); // Executes all updates.
                $this->manager->clear(); // Detaches all objects from Doctrine!

                // dealer is detached from doctrine, so we have to find it again
                $dealer = $this->ensureDealerAlreadyExists();
            }

            $progress->advance();
            ++$i;
        }

        $this->manager->flush();
        $this->removeOutOfCatalogDealerPrices($dealer);
        $this->manager->clear();
        $progress->finish();

        $output->writeln('');

        $matchingCountPercentage = $matchingCount * 100 / count($csvData);
        $output->writeLn('Prices matching a product: <info>'.$matchingCountPercentage.'%</info>');
    }

    /**
     * @return Dealer
     *
     * @throws \Exception
     */
    private function ensureDealerAlreadyExists()
    {
        $code = $this->input->getArgument('dealer');
        /** @var Dealer $dealer */
        $dealer = $this->dealerRepository->findOneBy(['code' => $code]);

        if (null === $dealer) {
            throw new \Exception(sprintf('Dealer with code %s does not exist', $code));
        }

        return $dealer;
    }

    /**
     * @return DealerPrice
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    private function createOrReplaceDealerPrice(array $data, Dealer $dealer)
    {
        /** @var DealerPrice $dealerPrice */
        $dealerPrice = $this->dealerPriceRepository->findOneBy(['url' => $data['url']]);

        if (null === $dealerPrice) {
            $dealerPrice = $this->dealerPriceFactory->createNew();
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

    private function formatPrice($price)
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
    private function getCsvData()
    {
        $filename = $this->input->getOption('filename');
        $data = [];

        foreach (file($filename) as $key => $row) {
            if ($this->input->getOption('remove-first-line') and 0 === $key) {
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
                case 'Indisponible':
                    $status = DealerPrice::STATUS_OUT_OF_STOCK;

                    break;
                case 'Pr?commande':
                case 'En précommande':
                case 'prcommande' === preg_replace('/[^a-z]/', '', $rowData[3]):
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
     * @return Product|null
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    private function findOneProductByData(array $data)
    {
        $product = $this->productRepository->findOneByBarcode($data['barcode']);

        if (null !== $product) {
            return $product;
        }

        $slug = Transliterator::transliterate($data['product_name']);
        $product = $this->productRepository->findOneBySlug($this->locale, $slug);

        return $product;
    }

    /**
     * @return int nbRows deleted
     *
     * @throws \Exception
     */
    private function removeOutOfCatalogDealerPrices(Dealer $dealer)
    {
        $query = $this->manager->createQuery('delete from App:DealerPrice o where o.dealer = :dealer and o.updatedAt < :today');

        return $query->execute([
            'dealer' => $dealer,
            'today' => (new \DateTime('today'))->format('Y-m-d H:i:s'),
        ]);
    }
}
