<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 02/03/2016
 * Time: 13:42
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Taxonomy\Model\Taxon as BaseTaxon;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="Taxon")
 */
class Taxon extends BaseTaxon
{
    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;
}