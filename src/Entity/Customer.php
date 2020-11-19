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
use JMS\Serializer\Annotation as JMS;
use Sylius\Component\Review\Model\ReviewerInterface;
use Sylius\Component\Customer\Model\Customer as BaseCustomer;
use Sylius\Component\User\Model\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="sylius_customer")
 *
 * @JMS\ExclusionPolicy("all")
 */
class Customer extends BaseCustomer implements CustomerInterface, ReviewerInterface
{
    /**
     * @var UserInterface
     *
     * @ORM\OneToOne(targetEntity="Sylius\Component\User\Model\UserInterface", mappedBy="customer", cascade={"persist"})
     *
     * @Assert\Valid(groups={"sylius"})
     */
    private $user;

    /**
     * @var Avatar
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Avatar" , cascade={"persist"})
     * @JMS\Expose
     */
    private $avatar;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true, unique=true)
     */
    private $code;

    public function getAvatar(): ?Avatar
    {
        return $this->avatar;
    }

    /**
     * @param Avatar $avatar
     */
    public function setAvatar(?Avatar $avatar): void
    {
        $this->avatar = $avatar;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): void
    {
        if ($this->user === $user) {
            return;
        }

        \Webmozart\Assert\Assert::nullOrIsInstanceOf($user, AppUserInterface::class);

        $previousUser = $this->user;
        $this->user = $user;

        if ($previousUser instanceof AppUserInterface) {
            $previousUser->setCustomer(null);
        }

        if ($user instanceof AppUserInterface) {
            $user->setCustomer($this);
        }
    }

    public function __toString(): string
    {
        if (null === $user = $this->user) {
            return parent::__toString();
        }

        return $user->getUsername();
    }
}
