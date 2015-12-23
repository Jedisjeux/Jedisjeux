<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 23/12/2015
 * Time: 13:55
 */

namespace AppBundle\Command;
use AppBundle\Entity\Country;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadCountriesCommand extends LoadYamlDataCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:countries:load')
            ->setDescription('Load countries');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<comment>Load countries</comment>");
        parent::execute($input, $output);

    }

    public function createEntityNewInstance()
    {
        return new Country();
    }

    public function getTableName()
    {
        return 'jdj_country';
    }

    public function getRepository()
    {
        return $this->getEntityManager()->getRepository('AppBundle:Country');
    }

    public function getYAMLFileName()
    {
        return $this->getContainer()->get('kernel')->getRootDir() . '/Resources/initialData/countries.yml';
    }

}