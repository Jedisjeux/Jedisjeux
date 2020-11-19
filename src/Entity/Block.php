<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

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
     * @var int
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
     * @ORM\ManyToOne(targetEntity="SlideShowBlock", inversedBy="blocks", cascade={"persist", "merge"})
     */
    protected $slideShowBlock;

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): void
    {
        $this->body = $body;
    }

    public function getImagePosition(): ?string
    {
        return $this->imagePosition;
    }

    public function setImagePosition(?string $imagePosition): void
    {
        $this->imagePosition = $imagePosition;
    }

    public function getClass(): ?string
    {
        return $this->class;
    }

    public function setClass(?string $class): void
    {
        $this->class = $class;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): void
    {
        $this->position = $position;
    }

    public function getImage(): ?BlockImage
    {
        return $this->image;
    }

    public function setImage(?BlockImage $image): void
    {
        $this->image = $image;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): void
    {
        $this->article = $article;
    }

    public function getSlideShowBlock(): ?SlideShowBlock
    {
        return $this->slideShowBlock;
    }

    public function setSlideShowBlock(?SlideShowBlock $slideShowBlock): void
    {
        $this->slideShowBlock = $slideShowBlock;
    }
}
