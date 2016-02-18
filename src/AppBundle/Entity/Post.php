<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 18/02/16
 * Time: 08:16
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Model\Identifiable;
use Knp\DoctrineBehaviors\Model\Blameable\Blameable;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table("jdj_post")
 */
class Post implements ResourceInterface
{
    use Identifiable,
        Blameable,
        Timestampable;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    protected $body;

    /**
     * @var Topic
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Topic")
     */
    protected $topic;

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
}