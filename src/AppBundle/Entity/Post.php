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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Blameable\Blameable;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\User\Model\CustomerInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table("jdj_post")
 */
class Post implements ResourceInterface
{
    use IdentifiableTrait,
        Timestampable;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true, unique=true)
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    protected $body;

    /**
     * @var Topic
     *
     * @ORM\OneToOne(targetEntity="Topic", mappedBy="mainPost")
     */
    protected $parent;

    /**
     * @var Topic
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Topic", inversedBy="posts")
     */
    protected $topic;

    /**
     * @var CustomerInterface
     *
     * @ORM\ManyToOne(targetEntity="Sylius\Component\User\Model\CustomerInterface")
     */
    protected $author;

    /**
     * @var Post
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Post", inversedBy="replies")
     */
    protected $replyTo;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Post", mappedBy="replyTo")
     */
    protected $replies;

    /**
     * Post constructor.
     */
    public function __construct()
    {
        $this->replies = new ArrayCollection();
    }

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
     * @return Post
     */
    public function setCode($code)
    {
        $this->code = $code;

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
     * @return $this
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return Topic
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Topic $parent
     *
     * @return $this
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Topic
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * @param Topic $topic
     *
     * @return $this
     */
    public function setTopic($topic)
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * @return CustomerInterface
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param CustomerInterface $author
     *
     * @return $this
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }
}