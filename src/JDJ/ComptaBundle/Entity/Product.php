<?php

namespace JDJ\ComptaBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 *
 * @ORM\Entity(repositoryClass="JDJ\ComptaBundle\Entity\Repository\ProductRepository")
 * @ORM\Table(name="cpta_product")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @Gedmo\Loggable
 */
class Product
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private $name;

    /**
     * @var float
     *
     * @Gedmo\Versioned
     * @ORM\Column(type="decimal", precision=6, scale=2, nullable=false)
     */
    private $price;

    /**
     * @var int
     *
     * @Gedmo\Versioned
     * @ORM\Column(type="integer", nullable=false)
     */
    private $subscriptionDuration;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="BillProduct", mappedBy="product")
     */
    protected $billProducts;

    /**
     * @ORM\Column(name="deletedAt", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * Product constructor.
     */
    public function __construct()
    {
        $this->billProducts = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return int
     */
    public function getSubscriptionDuration()
    {
        return $this->subscriptionDuration;
    }

    /**
     * @param int $subscriptionDuration
     * @return $this
     */
    public function setSubscriptionDuration($subscriptionDuration)
    {
        $this->subscriptionDuration = $subscriptionDuration;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getBillProducts()
    {
        return $this->billProducts;
    }

    /**
     * @param ArrayCollection $billProducts
     *
     * @return $this
     */
    public function setBillProducts($billProducts)
    {
        $this->billProducts = $billProducts;
        return $this;

    }

    /**
     * @return mixed
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @param mixed $deletedAt
     * @return $this
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
