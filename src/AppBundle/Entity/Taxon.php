<?php

/**
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
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
    // taxons of articles
    const CODE_ARTICLE = 'articles';
    const CODE_INTERVIEW = 'interviews';
    const CODE_IN_THE_BOXES = 'in-the-boxes';
    const CODE_NEWS = 'news';
    const CODE_PREVIEWS = 'previews';
    const CODE_REPORT_ARTICLE = 'report-articles';
    const CODE_REVIEW_ARTICLE = 'review-articles';

    // taxons of forum
    const CODE_FORUM = 'forum';

    // taxons of products
    const CODE_MECHANISM = 'mechanisms';
    const CODE_THEME = 'themes';
    const CODE_TARGET_AUDIENCE = 'target-audience';
    const CODE_CHILD = 'target-audiences-1';
    const CODE_BEGINNER = 'target-audiences-2';
    const CODE_ADVANCED_USER = 'target-audiences-3';
    const CODE_EXPERT = 'target-audiences-4';

    // taxons of people
    const CODE_ZONE = 'zones';

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $iconClass;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $color;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    protected $topicCount;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    protected $productCount;

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
        $this->productCount = 0;
    }

    /**
     * @return string
     *
     * @JMS\VirtualProperty
     * @JMS\SerializedName("permalink")
     * @JMS\Groups({"Details"})
     */
    public function getPermalink()
    {
        return parent::getPermalink();
    }

    /**
     * @return string
     *
     * @JMS\VirtualProperty
     * @JMS\SerializedName("name")
     * @JMS\Groups({"Details"})
     */
    public function getName()
    {
        return parent::getName();
    }

    /**
     * @return string
     */
    public function getIconClass()
    {
        return $this->iconClass;
    }

    /**
     * @param string $iconClass
     *
     * @return $this
     */
    public function setIconClass($iconClass)
    {
        $this->iconClass = $iconClass;

        return $this;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param string $color
     *
     * @return $this
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
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

    /**
     * @return mixed
     */
    public function getProductCount()
    {
        return $this->productCount;
    }

    /**
     * @param mixed $productCount
     *
     * @return $this
     */
    public function setProductCount($productCount)
    {
        $this->productCount = $productCount;

        return $this;
    }
}