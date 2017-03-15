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
     * {@inheritdoc}
     */
    public function getEmail()
    {
        return $this->customer->getEmail();
    }
    /**
     * {@inheritdoc}
     */
    public function setEmail($email)
    {
        $this->customer->setEmail($email);
    }

    /**
     * {@inheritdoc}
     */
    public function getEmailCanonical()
    {
        return $this->customer->getEmailCanonical();
    }

    /**
     * {@inheritdoc}
     */
    public function setEmailCanonical($emailCanonical)
    {
        $this->customer->setEmailCanonical($emailCanonical);
    }
}