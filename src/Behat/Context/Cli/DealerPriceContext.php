<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Context\Cli;

use App\Command\ImportDealersPricesCommand;
use App\Entity\Dealer;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\HttpKernel\KernelInterface;
use Webmozart\Assert\Assert;

class DealerPriceContext extends DefaultContext
{
    /**
     * @var RepositoryInterface
     */
    private $dealerPriceRepository;

    /**
     * @var ImportDealersPricesCommand
     */
    private $importDealersPricesCommand;

    /**
     * @param KernelInterface            $kernel
     * @param RepositoryInterface        $dealerPriceRepository
     * @param ImportDealersPricesCommand $importDealersPricesCommand
     */
    public function __construct(
        KernelInterface $kernel,
        RepositoryInterface $dealerPriceRepository,
        ImportDealersPricesCommand $importDealersPricesCommand
    ) {
        $this->dealerPriceRepository = $dealerPriceRepository;
        $this->importDealersPricesCommand = $importDealersPricesCommand;

        parent::__construct($kernel);
    }

    /**
     * @When /^I run import dealers prices command$/
     */
    public function iRunRunImportDealerPricesCommmandLine()
    {
        $this->application = new Application($this->kernel);

        $this->application->add($this->importDealersPricesCommand);

        $this->command = $this->application->find('app:dealers-prices:import');
        $this->setTester(new CommandTester($this->command));

        $this->iExecuteCommand('app:dealers-prices:import');
    }

    /**
     * @Then /^(this dealer) should(?:| also) have a (product "[^"]+") priced at "(?:€|£|\$)([^"]+)"$/
     */
    public function dealerShouldHaveProductNameWithPrice(Dealer $dealer, ProductInterface $product, string $price)
    {
        $price = ((float) $price) * 100;

        $dealerPrice = $this->dealerPriceRepository->findOneBy(['dealer' => $dealer, 'product' => $product, 'price' => $price]);

        Assert::notNull($dealerPrice);
    }

    /**
     * @Then /^(this dealer) should have no product anymore$/
     */
    public function dealerShouldHaveNoProductNameAnymore(Dealer $dealer)
    {
        $dealerPrices = $this->dealerPriceRepository->findBy(['dealer' => $dealer]);

        Assert::count($dealerPrices, 0);
    }

    /**
     * @param string $name
     */
    private function iExecuteCommand(string $name)
    {
        try {
            $this->getTester()->execute(['command' => $name]);
        } catch (\Exception $e) {
        }
    }
}
