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
    const CODE_FORUM = 'forum';

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    protected $topicCount;

    /**
     * Taxon constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->topicCount = 0;
    }

    /**
     * @return int
     */
    public function getTopicCount()
    {
        return $this->topicCount;
    }

    /**
     * @param int $topicCount
     */
    public function setTopicCount($topicCount)
    {
        $this->topicCount = $topicCount;
    }
}