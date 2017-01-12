<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 08/04/16
 * Time: 00:04
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Customer\Model\CustomerInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_player")
 */
class Player implements ResourceInterface
{
    use IdentifiableTrait;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    protected $score;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $name;

    /**
     * @var CustomerInterface
     *
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Customer\Model\CustomerInterface")
     */
    protected $customer;

    /**
     * @var GamePlay
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\GamePlay", inversedBy="players")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $gamePlay;

    /**
     * @return float
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * @param float $score
     *
     * @return $this
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        if ($this->getCustomer()) {
            return $this->getCustomer()->getUser()->getUsername();
        }

        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return CustomerInterface
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param CustomerInterface $customer
     *
     * @return $this
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;

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
}
