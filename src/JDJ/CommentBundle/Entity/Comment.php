<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 25/08/2014
 * Time: 22:43
 */

namespace JDJ\CommentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\CommentBundle\Entity\Comment as BaseComment;
use FOS\CommentBundle\Model\SignedCommentInterface;
use JDJ\UserBundle\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class Comment
 * @package JDJ\CommentBundle\Entity
 *
 * @ORM\Entity
 * @ORM\ChangeTrackingPolicy("DEFERRED_EXPLICIT")
 */
class Comment extends BaseComment implements SignedCommentInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Thread of this comment
     *
     * @var Thread
     * @ORM\ManyToOne(targetEntity="JDJ\CommentBundle\Entity\Thread")
     */
    protected $thread;

    /**
     * @var \JDJ\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="JDJ\UserBundle\Entity\User")
     */
    protected $author;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="JDJ\CoreBundle\Entity\Like", mappedBy="comment")
     */
    private $likes;

    /**
     * @param UserInterface $author
     * @return $this
     */
    public function setAuthor(UserInterface $author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return User|UserInterface
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return string
     */
    public function getAuthorName()
    {
        if (null === $this->getAuthor()) {
            return 'Anonymous';
        }

        return $this->getAuthor();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set thread
     *
     * @param \JDJ\CommentBundle\Entity\Thread $thread
     * @return Comment
     */


    /**
     * Get thread
     *
     * @return \JDJ\CommentBundle\Entity\Thread 
     */
    public function getThread()
    {
        return $this->thread;
    }

    /**
     * @return ArrayCollection
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * @param ArrayCollection $likes
     * @return $this
     */
    public function setLikes($likes)
    {
        $this->likes = $likes;

        return $this;
    }

    /**
     * @return int
     */
    public function getNbLikes()
    {
        $nb = 0;
        /** @var Like $like */
        foreach ($this->getLikes() as $like) {
            if ($like->isLike()) {
                $nb ++;
            }
        }
        return $nb;
    }

    /**
     * @return int
     */
    public function getNbDislikes()
    {
        $nb = 0;
        /** @var Like $like */
        foreach ($this->getLikes() as $like) {
            if (false === $like->isLike()) {
                $nb ++;
            }
        }
        return $nb;
    }



}
