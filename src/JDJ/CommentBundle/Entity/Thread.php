<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 25/08/2014
 * Time: 22:44
 */

namespace JDJ\CommentBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use FOS\CommentBundle\Entity\Thread as BaseThread;

/**
 * Class Thread
 * @package JDJ\CommentBundle\Entity
 */
class Thread extends BaseThread
{
    /**
     * @var int
     */
    protected $id;

    /**
     * Set id
     *
     * @param string $id
     * @return Thread
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return string 
     */
    public function getId()
    {
        return $this->id;
    }
}
