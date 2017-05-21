<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 14/03/2016
 * Time: 13:07
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Review\Model\ReviewerInterface;
use Sylius\Component\Customer\Model\Customer as BaseCustomer;
use Sylius\Component\User\Model\UserAwareInterface;
use Sylius\Component\User\Model\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="sylius_customer")
 */
class Customer extends BaseCustomer implements ReviewerInterface, UserAwareInterface
{
    /**
     * @var UserInterface
     *
     * @ORM\OneToOne(targetEntity="Sylius\Component\User\Model\UserInterface", mappedBy="customer", cascade={"persist"})
     *
     * @Assert\Valid()
     */
    private $user;

    /**
     * @var Avatar
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Avatar" , cascade={"persist"})
     */
    private $avatar;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true, unique=true)
     */
    private $code;

    /**
     * @return Avatar
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param Avatar $avatar
     *
     * @return $this
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param UserInterface $user
     *
     * @return Customer
     */
    public function setUser(UserInterface $user = null)
    {
        if ($this->user !== $user) {
            $this->user = $user;
            $this->assignCustomer($user);
        }
    }

    /**
     * @param User|null $user
     */
    protected function assignCustomer($user = null)
    {
        if (null !== $user) {
            $user->setCustomer($this);
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        if (null === $user = $this->user) {
            return parent::__toString();
        }

        return $user->getUsername();
    }
}
