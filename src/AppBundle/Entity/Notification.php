<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 23/10/2015
 * Time: 16:12
 */

namespace AppBundle\Entity;

use AppBundle\Model\Identifiable;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Knp\DoctrineBehaviors\Model\Blameable\Blameable;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\User\Model\CustomerInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 *
 * @ExclusionPolicy("all")
 */
class Notification implements ResourceInterface
{
    use Identifiable,
        Timestampable;

    /**
     * @var CustomerInterface
     *
     * @ORM\ManyToOne(
     *      targetEntity="Sylius\Component\User\Model\CustomerInterface"
     * )
     * @ORM\JoinColumn(nullable=false)
     */
    protected $recipient;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", name="is_read")
     */
    protected $read;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     * @Expose
     */
    protected $message;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Expose
     */
    protected $target;

    /**
     * @var Topic
     *
     * @ORM\ManyToOne(targetEntity="Topic")
     * @Expose
     */
    protected $topic;

    /**
     * Notification constructor.
     */
    public function __construct()
    {
        $this->read = false;
    }

    /**
     * @return CustomerInterface
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @param CustomerInterface $recipient
     *
     * @return $this
     */
    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isRead()
    {
        return $this->read;
    }

    /**
     * @param boolean $read
     *
     * @return $this
     */
    public function setRead($read)
    {
        $this->read = $read;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     *
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param string $target
     *
     * @return $this
     */
    public function setTarget($target)
    {
        $this->target = $target;

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
