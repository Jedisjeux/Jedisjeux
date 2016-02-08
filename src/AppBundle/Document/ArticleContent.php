<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 20/01/2016
 * Time: 13:45
 */

namespace AppBundle\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\ContainerBlock;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\ImagineBlock;


/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @PHPCR\Document(referenceable=true)
 */
class ArticleContent extends ContainerBlock
{
    /**
     * state constants
     */
    const WRITING = "writing";
    const NEED_A_REVIEW = "need_a_review";
    const READY_TO_PUBLISH = "ready_to_publish";
    const PUBLISHED = "published";


    /**
     * @var string
     *
     * @PHPCR\String(nullable=false)
     */
    protected $title;

    /**
     * @var string
     *
     * @PHPCR\String(nullable=false)
     */
    protected $state = null;

    /**
     * @var ImagineBlock
     *
     * @PHPCR\Child()
     */
    protected $mainImage;

    /**
     * Article constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->state = self::WRITING;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state
     *
     * @return $this
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return ImagineBlock
     */
    public function getMainImage()
    {
        return $this->mainImage;
    }

    /**
     * @param ImagineBlock $mainImage
     *
     * @return $this
     */
    public function setMainImage($mainImage)
    {
        $this->mainImage = $mainImage;

        return $this;
    }
}