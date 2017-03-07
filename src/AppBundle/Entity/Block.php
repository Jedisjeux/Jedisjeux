<?php

/*
 * This file is part of jdj project.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_block")
 */
class Block implements ResourceInterface
{
    use IdentifiableTrait;

    const POSITION_LEFT = 'left';
    const POSITION_RIGHT = 'right';
    const POSITION_TOP = 'top';

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $body;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $imagePosition;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $class;

    /**
     * @var integer
     *
     * @Gedmo\SortablePosition
     * @ORM\column(type="integer")
     */
    protected $position;

    /**
     * @var BlockImage
     *
     * @ORM\OneToOne(targetEntity="BlockImage", cascade={"persist", "merge"})
     */
    protected $image;

    /**
     * @var Article
     *
     * @Gedmo\SortableGroup
     * @ORM\ManyToOne(targetEntity="Article", inversedBy="blocks")
     */
    protected $article;

    /**
     * @var SlideShowBlock
     *
     * @Gedmo\SortableGroup
     * @ORM\ManyToOne(targetEntity="SlideShowBlock", inversedBy="blocks")
     */
    protected $slideShowBlock;

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
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
     * @return Block
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     *
     * @return Block
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return string
     */
    public function getImagePosition()
    {
        return $this->imagePosition;
    }

    /**
     * @param string $imagePosition
     *
     * @return $this
     */
    public function setImagePosition($imagePosition)
    {
        $this->imagePosition = $imagePosition;

        return $this;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $class
     *
     * @return Block
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param int $position
     *
     * @return Block
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return BlockImage
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param BlockImage $image
     *
     * @return Block
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @param Article $article
     *
     * @return Block
     */
    public function setArticle($article)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * @return SlideShowBlock
     */
    public function getSlideShowBlock(): SlideShowBlock
    {
        return $this->slideShowBlock;
    }

    /**
     * @param SlideShowBlock $slideShowBlock
     *
     * @return $this
     */
    public function setSlideShowBlock($slideShowBlock)
    {
        $this->slideShowBlock = $slideShowBlock;

        return $this;
    }
}
