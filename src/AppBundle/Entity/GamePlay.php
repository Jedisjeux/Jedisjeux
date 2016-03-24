<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 18/03/2016
 * Time: 13:25
 */

namespace AppBundle\Entity;

use AppBundle\Model\Identifiable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\User\Model\CustomerInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_game_play")
 */
class GamePlay implements ResourceInterface
{
    use Identifiable,
        Timestampable;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true, unique=true)
     */
    protected $code;

    /**
     * @var ProductInterface
     *
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Product\Model\ProductInterface")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $product;

    /**
     * @var CustomerInterface
     *
     * @ORM\ManyToOne(targetEntity="Sylius\Component\User\Model\CustomerInterface")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $author;

    /**
     * @var Topic
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Topic", inversedBy="gamePlay")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $topic;

    /**
     * @var ArrayCollection|GamePlayImage[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\GamePlayImage", mappedBy="gamePlay")
     */
    protected $images;

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return ProductInterface
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param ProductInterface $product
     *
     * @return $this
     */
    public function setProduct($product)
    {
        $this->product = $product;

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