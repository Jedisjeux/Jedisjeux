<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\GamePlay", inversedBy="players")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $gamePlay;

    public function getScore(): ?float
    {
        return $this->score;
    }

    public function setScore(?float $score): void
    {
        $this->score = $score;
    }

    public function getName(): ?string
    {
        if ($this->getCustomer()) {
            return $this->getCustomer()->getUser()->getUsername();
        }

        return $this->name;
    }

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

    public function setCustomer(?CustomerInterface $customer): void
    {
        $this->customer = $customer;
    }

    public function getGamePlay(): ?GamePlay
    {
        return $this->gamePlay;
    }

    public function setGamePlay(?GamePlay $gamePlay): void
    {
        $this->gamePlay = $gamePlay;
    }
}
