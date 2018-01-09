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
 * @ORM\Table(name="sylius_taxon")
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
    const CODE_VIDEO = 'videos';

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
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $public;

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
     * Taxon constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->topicCount = 0;
        $this->productCount = 0;
        $this->public = true;
    }

    /**
     * @return string
     *
     * @JMS\VirtualProperty
     * @JMS\Groups({"Default", "Autocomplete"})
     */
    public function getCode(): ?string
    {
        return parent::getCode();
    }

    /**
     * @return string
     *
     * @JMS\VirtualProperty
     * @JMS\Groups({"Detailed", "Autocomplete"})
     */
    public function getName(): ?string
    {
        return parent::getName();
    }

    /**
     * @return string|null
     */
    public function getIconClass(): ?string
    {
        return $this->iconClass;
    }

    /**
     * @param string|null $iconClass
     */
    public function setIconClass(?string $iconClass): void
    {
        $this->iconClass = $iconClass;
    }

    /**
     * @return string|null
     */
    public function getColor(): ?string
    {
        return $this->color;
    }

    /**
     * @param string|null $color
     */
    public function setColor(?string $color): void
    {
        $this->color = $color;
    }

    /**
     * @return boolean
     */
    public function isPublic(): bool
    {
        return $this->public;
    }

    /**
     * @param boolean $public
     */
    public function setPublic(bool $public): void
    {
        $this->public = $public;
    }

    /**
     * @return int
     */
    public function getTopicCount(): int
    {
        return $this->topicCount;
    }

    /**
     * @param int $topicCount
     */
    public function setTopicCount(int $topicCount): void
    {
        $this->topicCount = $topicCount;
    }

    /**
     * @return int
     */
    public function getProductCount(): int
    {
        return $this->productCount;
    }

    /**
     * @param int $productCount
     */
    public function setProductCount(int $productCount): void
    {
        $this->productCount = $productCount;
    }
}