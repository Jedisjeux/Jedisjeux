<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 22/12/2015
 * Time: 13:18
 */

namespace AppBundle\Command;

use Doctrine\ORM\EntityRepository;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
interface LoadCommandInterface
{
    public function createEntityNewInstance();

    /**
     * @return EntityRepository
     */
    public function getRepository();
}