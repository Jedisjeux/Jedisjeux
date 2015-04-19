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
use FOS\CommentBundle\Model\VotableCommentInterface;
use JDJ\UserBundle\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class Comment
 * @package JDJ\CommentBundle\Entity
 *
 * @ORM\Entity
 * @ORM\ChangeTrackingPolicy("DEFERRED_EXPLICIT")
 */
class Comment extends BaseComment implements SignedCommentInterface,VotableCommentInterface
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
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $score = 0;

    /**
     * Sets the score of the comment.
     *
     * @param integer $score
     */
    public function setScore($score)
    {
        $this->score = $score;
    }

    /**
     * Returns the current score of the comment.
     *
     * @return integer
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Increments the comment score by the provided
     * value.
     *
     * @param integer value
     *
     * @return integer The new comment score
     */
    public function incrementScore($by = 1)
    {
        $this->score += $by;
    }

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

        return $this->getAuthor()->getUsername();
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


}
