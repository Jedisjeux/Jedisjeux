<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 14/12/2015
 * Time: 12:17
 */

namespace JDJ\ComptaBundle\Command;

use Doctrine\ORM\EntityManager;
use JDJ\ComptaBundle\Entity\Address;
use JDJ\ComptaBundle\Entity\Dealer;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadDealersCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('compta:dealers:load')
            ->setDescription('Load dealers');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Loading dealers");

        $createdItemCount = 0;

        foreach ($this->getDealers() as $name => $data) {
            $dealer = $this->createDealer($name, $data);

            if (null !== $dealer) {
                $createdItemCount ++;
            }
        }

        $output->writeln("<comment>" . $createdItemCount . " items created</comment>");

    }

    protected function getDealers()
    {
        return array(
            'Jedisjeux' => array(
                'address' => array(
                    'street' => '2 allée de la châtaigneraie',
                    'more' => 'bât C002',
                    'postal_code' => '35740',
                    'city' => 'Pacé',
                ),
            ),
        );
    }

    /**
     * @param string $name
     * @param array $data
     * @return Dealer|null
     */
    protected function createDealer($name, array $data)
    {
        /** @var Dealer $dealer */
        $dealer = $this->getDealerRepository()->findOneBy(array('name' => $name));

        if (null !== $dealer) {
            return null;
        }

        $dealer = new Dealer();
        $dealer
            ->setAddress(new Address());

        $addressData = $data['address'];

        $dealer
            ->setName($name)
            ->getAddress()
            ->setStreet($addressData['street'])
            ->setAdditionalAddressInfo($addressData['more'])
            ->setPostalCode($addressData['postal_code'])
            ->setCity($addressData['city']);

        $this->getEntityManager()->persist($dealer);
        $this->getEntityManager()->flush();

        return $dealer;
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getDealerRepository()
    {
        return $this->getEntityManager()->getRepository('JDJComptaBundle:Dealer');
    }
}