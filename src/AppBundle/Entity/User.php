<?php

/**
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\User\Model\User as BaseUser;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 *
 * @ORM\Entity
 * @ORM\Table("sylius_user")
 * @UniqueEntity(
 *     fields={"username"},
 *     errorPath="username",
 *     message="app.user.username.unique",
 *     groups="sylius"
 * )
 */
class User extends BaseUser
{
    /**
     * @var CustomerInterface
     *
     * @ORM\OneToOne(targetEntity="Sylius\Component\Customer\Model\CustomerInterface", inversedBy="user", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    protected $customer;

    /**
     * @return CustomerInterface|null
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
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->customer->getEmail();
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->customer->setEmail($email);
    }

    /**
     * @return string|null
     */
    public function getEmailCanonical(): ?string
    {
        return $this->customer->getEmailCanonical();
    }

    /**
     * @param string|null $emailCanonical
     */
    public function setEmailCanonical(?string $emailCanonical): void
    {
        $this->customer->setEmailCanonical($emailCanonical);
    }
}