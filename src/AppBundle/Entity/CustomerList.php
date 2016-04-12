<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 12/04/2016
 * Time: 10:03
 */

namespace AppBundle\Entity;

use AppBundle\Model\Identifiable;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\User\Model\CustomerInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_customer_list")
 */
class CustomerList implements ResourceInterface
{
    use Identifiable,
        Timestampable;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $name;

    /**
     * @var CustomerInterface
     *
     * @ORM\ManyToOne(targetEntity="Sylius\Component\User\Model\CustomerInterface")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $customer;
}