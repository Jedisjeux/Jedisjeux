<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 21/12/2015
 * Time: 13:23
 */

namespace AppBundle\Command\Installer\Data;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Parser;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
abstract class LoadYamlDataCommand extends LoadCommand implements LoadYamlDataInterface, LoadCommandInterface
{
    /**
     * @var OutputInterface
     */
    protected $output;


    public function getRows()
    {
        return $this->parse();
    }

    /**
     * Parse YAML File
     *
     * @return mixed
     */
    public function parse()
    {
        $yaml = new Parser();
        return $yaml->parse(file_get_contents($this->getYAMLFileName()));
    }
}