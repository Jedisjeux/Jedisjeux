<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 21/08/2014
 * Time: 21:38
 */

namespace AppBundle\Command;


use Doctrine\ORM\EntityRepository;

interface LoadYamlDataInterface
{
    public function getYAMLFileName();
    public function getTableName();
}