<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 15/06/2015
 * Time: 23:57
 */

namespace JDJ\ComptaBundle\Command;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use JDJ\ComptaBundle\Entity\PaymentMethod;


/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class PaymentMethodImportCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('compta:payment-method:import')
            ->setDescription('Import payment methods from old jedisjeux')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Importing payment methods from old jedisjeux");
        $query = <<<EOM
select      old.*
from        zf_jedisjeux_test.cpta_modeReglement old
EOM;

        $oldItems = $this->getDatabaseConnection()->fetchAll($query);
        $output->writeln("<comment>total of " . count($oldItems) . " items</comment>");

        $createdItemCount = 0;
        $updatedItemCount = 0;

        foreach($oldItems as $data) {

            /** @var PaymentMethod $paymentMethod */
            $paymentMethod = $this->getPaymentMethodRepository()->find($data['id']);
            if (null === $paymentMethod) {
                $paymentMethod = new PaymentMethod();
                $this->getEntityManager()->persist($paymentMethod);
                $createdItemCount ++;
            } else {
                $updatedItemCount ++;
            }

            $paymentMethod
                ->setId($data['id'])
                ->setName($data['libelle']);

            $this->getEntityManager()->flush();

            $this->getDatabaseConnection()->update('cpta_payment_method', array(
                "id" => $data['id'],
            ), array('id' => $paymentMethod->getId()));

            $autoIncrement = $data['id'] + 1;
            $this->getDatabaseConnection()->exec("ALTER TABLE cpta_payment_method AUTO_INCREMENT = " . $autoIncrement );

        }

        $output->writeln("<comment>" . $createdItemCount . " items created</comment>");
        $output->writeln("<comment>" . $updatedItemCount . " items updated</comment>");
        $output->writeln("<info>Importing payment methods OK</info>");
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
    protected function getPaymentMethodRepository()
    {
        return $this->getEntityManager()->getRepository('JDJComptaBundle:PaymentMethod');
    }
}