<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 12/04/2016
 * Time: 13:27
 */

namespace AppBundle\Command\Installer\Data;

use AppBundle\Entity\CustomerListElement;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadGameLibrariesCommand extends ContainerAwareCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:game-libraries:load')
            ->setDescription('Loading game libraries');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<comment>" . $this->getDescription() . "</comment>");

        /** @var CustomerListElement $customerListElement */
        $customerListElement = $this->getRepository()->find(1);

        var_dump((string) $customerListElement->getElement());
    }

    /**
     * @return EntityRepository
     */
    protected function getRepository()
    {
        return $this->getContainer()->get('app.repository.customer_list_element');
    }
}