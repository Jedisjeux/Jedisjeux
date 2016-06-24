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
use Sylius\Component\Taxonomy\Model\TaxonInterface;

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
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Post", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    protected $mainPost;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Post", mappedBy="topic", cascade={"persist", "remove"})
     */
    protected $posts;

    /**
     * @var Taxon
     *
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Taxonomy\Model\TaxonInterface")
     */
    protected $mainTaxon;

    /**
     * @var GamePlay
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\GamePlay", mappedBy="topic")
     */
    protected $gamePlay;

    /**
     * @var Article
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Article", mappedBy="topic")
     */
    protected $article;

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
     *
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
     *
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

    /**
     * @param $post
     *
     * @return $this
     */
    public function addPost(Post $post)
    {
        if (!$this->posts->contains($post)) {
            $post->setTopic($this);
            $this->posts->add($post);
        }

        return $this;
    }

    /**
     * @return Taxon|TaxonInterface
     */
    public function getMainTaxon()
    {
        return $this->mainTaxon;
    }

    /**
     * @param TaxonInterface $mainTaxon
     *
     * @return $this
     */
    public function setMainTaxon($mainTaxon)
    {
        $this->mainTaxon = $mainTaxon;

        return $this;
    }

    /**
     * @return GamePlay
     */
    public function getGamePlay()
    {
        return $this->gamePlay;
    }

    /**
     * @param GamePlay $gamePlay
     *
     * @return $this
     */
    public function setGamePlay($gamePlay)
    {
        $this->gamePlay = $gamePlay;

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
     * @return $this
     */
    public function setArticle($article)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle();
    }
}
