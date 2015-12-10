<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 19/05/2015
 * Time: 15:05
 */

namespace JDJ\ComptaBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="cpta_bill_product",uniqueConstraints={
 *     @ORM\UniqueConstraint(
 *          name="uniq_bill_product_idx",
 *          columns={"bill_id", "product_id"}
 *      )
 * })
 */
class BillProduct 
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
     * @var Bill
     *
     * @ORM\ManyToOne(targetEntity="Bill", inversedBy="billProducts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $bill;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="Product", cascade={"persist"}, inversedBy="billProducts")
     */
    private $product;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $productVersion;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @var Subscription[]
     *
     * @ORM\OneToMany(targetEntity="Subscription", mappedBy="billProduct")
     */
    private $subscriptions;

    /**
     * BillProduct constructor.
     */
    public function __construct()
    {
        $this->subscriptions = new ArrayCollection();
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
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return Bill
     */
    public function getBill()
    {
        return $this->bill;
    }

    /**
     * @param Bill $bill
     * @return $this
     */
    public function setBill($bill)
    {
        $this->bill = $bill;

        return $this;
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param Product $product
     * @return $this
     */
    public function setProduct(Product $product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return int
     */
    public function getProductVersion()
    {
        return $this->productVersion;
    }

    /**
     * @param int $productVersion
     * @return $this
     */
    public function setProductVersion($productVersion)
    {
        $this->productVersion = $productVersion;

        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     * @return $this
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return Subscription[]
     */
    public function getSubscriptions()
    {
        return $this->subscriptions;
    }

    /**
     * @param Subscription[] $subscriptions
     * @return $this
     */
    public function setSubscriptions($subscriptions)
    {
        $this->subscriptions = $subscriptions;

        return $this;
    }
}