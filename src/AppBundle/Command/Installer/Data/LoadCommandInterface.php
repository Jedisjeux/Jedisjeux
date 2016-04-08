<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 22/12/2015
 * Time: 13:18
 */

namespace AppBundle\Command\Installer\Data;

use Doctrine\ORM\EntityRepository;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
interface LoadCommandInterface
{
    /**
     * @return array
     */
    public function getRows();

    /**
     * @return object
     */
    public function createEntityNewInstance();

    /**
     * @return string
     */
    public function getTableName();

    /**
     * @return EntityRepository
     */
    public function getRepository();
}