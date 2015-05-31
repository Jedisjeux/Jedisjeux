<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 19/05/2015
 * Time: 15:05
 */

namespace JDJ\ComptaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BillProduct
 *
 * @ORM\Entity
 * @ORM\Table(name="cpta_bill_product")
 */
class BillProduct 
{
    /**
     * @var Bill
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Bill", inversedBy="billProducts")
     */
    private $bill;

    /**
     * @var Product
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Product")
     */
    private $product;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $productVersion;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $quantity;

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
    public function setProduct($product)
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
}