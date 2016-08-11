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
use Sylius\Component\Taxonomy\Model\TaxonInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="Taxon")
 */
class Taxon extends BaseTaxon
{
    const CODE_ARTICLE = 'articles';
    const CODE_FORUM = 'forum';
    const CODE_INTERVIEW = 'interviews';
    const CODE_IN_THE_BOXES = 'in-the-boxes';
    const CODE_MECHANISM = 'mechanisms';
    const CODE_NEWS = 'news';
    const CODE_PREVIEWS = 'previews';
    const CODE_REPORT_ARTICLE = 'report-articles';
    const CODE_REVIEW_ARTICLE = 'review-articles';
    const CODE_THEME = 'themes';
    const CODE_ZONE = 'zones';

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    protected $topicCount;

    /**
     * {@inheritdoc}
     */
    public function setParent(TaxonInterface $parent = null)
    {
        if ($parent !== $this->parent) {
            $this->setPermalink(null);
        }

        parent::setParent($parent);
    }

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