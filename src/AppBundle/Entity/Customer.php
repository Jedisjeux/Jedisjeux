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
use Sylius\Component\User\Model\Customer as BaseCustomer;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="sylius_customer")
 */
class Customer extends BaseCustomer implements ReviewerInterface
{
    /**
     * @var Avatar
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Avatar" , cascade={"persist"})
     */
    private $avatar;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
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
}