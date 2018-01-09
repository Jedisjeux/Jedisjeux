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
     * @return float|null
     */
    public function getScore(): ?float
    {
        return $this->score;
    }

    /**
     * @param float|null $score
     */
    public function setScore(?float $score): void
    {
        $this->score = $score;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        if ($this->getCustomer()) {
            return $this->getCustomer()->getUser()->getUsername();
        }

        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return Customer|CustomerInterface|null
     */
    public function getCustomer(): ?CustomerInterface
    {
        return $this->customer;
    }

    /**
     * @param CustomerInterface|null $customer
     */
    public function setCustomer(?CustomerInterface $customer): void
    {
        $this->customer = $customer;
    }

    /**
     * @return GamePlay|null
     */
    public function getGamePlay(): ?GamePlay
    {
        return $this->gamePlay;
    }

    /**
     * @param GamePlay|null $gamePlay
     */
    public function setGamePlay(?GamePlay $gamePlay): void
    {
        $this->gamePlay = $gamePlay;
    }
}
