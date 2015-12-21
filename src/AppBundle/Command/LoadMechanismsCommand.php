<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 21/12/2015
 * Time: 13:00
 */

namespace AppBundle\Command;

use Doctrine\ORM\EntityManager;
use JDJ\JeuBundle\Entity\Mechanism;
use JDJ\WebBundle\DataFixtures\LoadEntityYMLDataInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Parser;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadMechanismsCommand extends LoadYamlDataCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:mechanisms:load')
            ->setDescription('Load mechanisms');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<comment>Load mechanisms</comment>");
        parent::execute($input, $output);

    }

    public function getTableName()
    {
        return "jdj_mechanism";
    }

    public function getYAMLFileName()
    {
        return $this->getContainer()->get('kernel')->getRootDir() . '/Resources/initialData/mechanisms.yml';
    }

    public function createEntityNewInstance()
    {
        return new Mechanism();
    }

    public function getRepository()
    {
        return $this->getContainer()->get('doctrine')->getRepository('JDJJeuBundle:Mechanism');
    }
}