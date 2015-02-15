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
 */
class Comment extends BaseComment implements SignedCommentInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * Thread of this comment
     *
     * @var Thread
     */
    protected $thread;

    /**
     * Author of the comment
     *
     * @var User
     */
    protected $author;

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
