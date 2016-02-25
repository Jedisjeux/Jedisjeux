<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 17/02/2016
 * Time: 13:35
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Model\Identifiable;
use Knp\DoctrineBehaviors\Model\Blameable\Blameable;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 *
 * @ORM\Entity
 * @ORM\Table("jdj_topic")
 */
class Topic implements ResourceInterface
{
    use Identifiable,
        Blameable,
        Timestampable;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false)
     */
    protected $title;

    /**
     * @var Post
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Post")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $mainPost;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Post", mappedBy="topic")
     */
    protected $posts;

    /**
     * Topic constructor.
     */
    public function __construct()
    {
        $this->posts = new ArrayCollection();
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
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Post
     */
    public function getMainPost()
    {
        return $this->mainPost;
    }

    /**
     * @param Post $mainPost
     * @return $this
     */
    public function setMainPost($mainPost)
    {
        $this->mainPost = $mainPost;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * @param ArrayCollection $posts
     *
     * @return $this
     */
    public function setPosts($posts)
    {
        $this->posts = $posts;

        return $this;
    }

    public function __toString()
    {
        return $this->getTitle();
    }
}
