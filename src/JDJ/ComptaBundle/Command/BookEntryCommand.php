<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 16/06/2015
 * Time: 00:22
 */

namespace JDJ\ComptaBundle\Command;
use JDJ\ComptaBundle\Entity\PaymentMethod;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use JDJ\ComptaBundle\Entity\BookEntry;
use Doctrine\ORM\EntityManager;


/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class BookEntryCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('compta:book-entry:import')
            ->setDescription('Import book entries from old jedisjeux')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Importing book entries from old jedisjeux");
        $query = <<<EOM
select      old.*
from        zf_jedisjeux_test.cpta_ecriture old
order by    old.id
EOM;

        $oldItems = $this->getDatabaseConnection()->fetchAll($query);
        $output->writeln("<comment>total of " . count($oldItems) . " items</comment>");

        $createdItemCount = 0;
        $updatedItemCount = 0;

        foreach($oldItems as $data) {

            /** @var BookEntry $bookEntry */
            $bookEntry = $this->getBookEntryRepository()->find($data['id']);
            if (null === $bookEntry) {
                $bookEntry = new BookEntry();
                $this->getEntityManager()->persist($bookEntry);
                $createdItemCount ++;
            } else {
                $updatedItemCount ++;
            }

            /** @var PaymentMethod $paymentMethod */
            $paymentMethod = $this->getPaymentMethodRepository()->find($data['idModeReglement']);

            $bookEntry
                //->setId($data['id'])
                ->setLabel($data['libelle'])
                ->setPrice($data['montant'])
                ->setPaymentMethod($paymentMethod)
                ->setCredit($data['sens'] === 'credit' ? true : false)
                ->setCreditedAt($data['sens'] === 'credit' ? \DateTime::createFromFormat('Y-m-d H:i:s', $data['dateCreation']) : null)
                ->setDebitedAt($data['sens'] === 'debit' ? \DateTime::createFromFormat('Y-m-d H:i:s', $data['dateCreation']) : null);

            $this->getEntityManager()->flush();

            $this->getDatabaseConnection()->update('cpta_book_entry', array(
                "id" => $data['id'],
            ), array('id' => $bookEntry->getId()));

            $autoIncrement = $data['id'] + 1;
            $this->getDatabaseConnection()->exec("ALTER TABLE cpta_book_entry AUTO_INCREMENT = " . $autoIncrement );

        }

        $output->writeln("<comment>" . $createdItemCount . " items created</comment>");
        $output->writeln("<comment>" . $updatedItemCount . " items updated</comment>");
        $output->writeln("<info>Importing book entries OK</info>");
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    protected function getDatabaseConnection()
    {
        return $this->getContainer()->get('database_connection');
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
    protected function getBookEntryRepository()
    {
        return $this->getEntityManager()->getRepository('JDJComptaBundle:BookEntry');
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getPaymentMethodRepository()
    {
        return $this->getEntityManager()->getRepository('JDJComptaBundle:PaymentMethod');
    }
}