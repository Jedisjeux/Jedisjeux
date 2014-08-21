<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 21/08/2014
 * Time: 21:38
 */

namespace JDJ\WebBundle\DataFixtures;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Yaml\Parser;

abstract class LoadEntityYMLData extends ContainerAware implements FixtureInterface, OrderedFixtureInterface, LoadEntityYMLDataInterface
{

    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $rows = $this->parse();
        foreach($rows as $data)
        {
            $this->populateData($data, $this->getEntityNewInstance());
        }
        $this->getManager()->flush();
    }

    /**
     * Parse YAML File
     *
     * @return mixed
     */
    private function parse()
    {
        $yaml = new Parser();
        return $yaml->parse(file_get_contents($this->getYAMLFileName()));
    }

    /**
     * Populate Data to Entity
     *
     * @param $data
     * @param $entity
     */
    private function populateData($data, $entity)
    {
        $entityReflection = new \ReflectionClass($entity);
        foreach($data as $key=>$value)
        {
            if ($entityReflection->hasProperty($key)) {
                $property = $entityReflection->getProperty($key);
                $property->setAccessible(true);
                $property->setValue($entity, $value);
            }
        }
        $this->getManager()->persist($entity);
    }

} 