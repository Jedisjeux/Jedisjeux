<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 19/05/2015
 * Time: 15:05
 */

namespace JDJ\ComptaBundle\Entity;

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
     * @ORM\ManyToOne(targetEntity="Customer")
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
     * @var Product
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Product")
     */
    private $productPrice;
}