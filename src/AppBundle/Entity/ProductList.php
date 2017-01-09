<?php

/*
 * This file is part of jdj.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_product_list")
 */
class ProductList implements ResourceInterface
{
    use IdentifiableTrait,
        Timestampable;

    const CODE_GAME_LIBRARY = 'game_library';

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
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Customer\Model\CustomerInterface")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $owner;

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return CustomerInterface
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param CustomerInterface $owner
     */
    public function setOwner(CustomerInterface $owner = null)
    {
        $this->owner = $owner;
    }
}
