<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 10/02/2016
 * Time: 13:51
 */

namespace AppBundle\Command;
use JDJ\UserReviewBundle\Entity\Note;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadRatingsCommand extends LoadYamlDataCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:ratings:load')
            ->setDescription('Load ratings');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<comment>".$this->getDescription()."</comment>");
        parent::execute($input, $output);

    }

    public function getTableName()
    {
        return "jdj_mechanism";
    }

    public function getYAMLFileName()
    {
        return $this->getContainer()->get('kernel')->getRootDir() . '/Resources/initialData/ratings.yml';
    }

    public function createEntityNewInstance()
    {
        return new Note();
    }

    public function getRepository()
    {
        return $this->getContainer()->get('doctrine')->getRepository('JDJUserReviewBundle:Note');
    }
}