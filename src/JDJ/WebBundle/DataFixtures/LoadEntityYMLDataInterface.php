<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 21/08/2014
 * Time: 21:38
 */

namespace JDJ\WebBundle\DataFixtures;


interface LoadEntityYMLDataInterface
{
    public function getYAMLFileName();
    public function getEntityNewInstance();
}