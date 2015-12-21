<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 21/12/2015
 * Time: 13:52
 */

namespace AppBundle\Command;
use JDJ\JeuBundle\Entity\Theme;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadThemesCommand extends LoadYamlDataCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:themes:load')
            ->setDescription('Load themes');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<comment>Load themes</comment>");
        parent::execute($input, $output);

    }

    public function getTableName()
    {
        return "jdj_theme";
    }

    public function getYAMLFileName()
    {
        return $this->getContainer()->get('kernel')->getRootDir() . '/Resources/initialData/themes.yml';
    }

    public function createEntityNewInstance()
    {
        return new Theme();
    }

    public function getRepository()
    {
        return $this->getContainer()->get('doctrine')->getRepository('JDJJeuBundle:Theme');
    }
}