<?php

namespace JDJ\ComptaBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 *
 * @ORM\Entity(repositoryClass="JDJ\ComptaBundle\Entity\Repository\BillRepository")
 * @ORM\Table(name="cpta_bill")
 */
class Bill
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
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $paidAt;

    /**
     * @var \DateTime $createdAt
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var Customer
     *
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy="bills", cascade={"persist", "merge"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    /**
     * @var BillProduct[]
     *
     * @ORM\Id
     * @ORM\OneToMany(targetEntity="BillProduct", mappedBy="bill", cascade={"persist", "merge", "remove"})
     */
    private $billProducts;

    /**
     * @var PaymentMethod
     *
     * @ORM\ManyToOne(targetEntity="PaymentMethod")
     * @ORM\JoinColumn(nullable=false)
     */
    private $paymentMethod;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $customerAddressVersion;

    /**
     * @var Subscription[]
     *
     * @ORM\OneToMany(targetEntity="Subscription", mappedBy="bill", cascade={"persist", "merge"})
     */
    private $subscriptions;

    /**
     * @var BookEntry
     *
     * @ORM\OneToOne(targetEntity="BookEntry")
     * @ORM\JoinColumn(nullable=true)
     */
    private $bookEntry;

    /**
     * @var float
     */
    private $totalPrice;

    /**
     * Constructor
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
     * @return \DateTime
     */
    public function getPaidAt()
    {
        return $this->paidAt;
    }

    /**
     * @param \DateTime $paidAt
     * @return $this
     */
    public function setPaidAt($paidAt)
    {
        $this->paidAt = $paidAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     * @return $this
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return BillProduct[]
     */
    public function getBillProducts()
    {
        return $this->billProducts;
    }

    /**
     * @param ArrayCollection $billProducts
     * @return $this
     */
    public function setBillProducts($billProducts)
    {
        $this->billProducts = $billProducts;

        return $this;
    }

    /**
     * @param BillProduct $billProduct
     * @return $this
     */
    public function addBillProduct(BillProduct $billProduct)
    {
        $this->billProducts[] = $billProduct;

        return $this;
    }

    /**
     * @return PaymentMethod
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * @param PaymentMethod $paymentMethod
     * @return $this
     */
    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    /**
     * @return int
     */
    public function getCustomerAddressVersion()
    {
        return $this->customerAddressVersion;
    }

    /**
     * @param int $customerAddressVersion
     * @return $this
     */
    public function setCustomerAddressVersion($customerAddressVersion)
    {
        $this->customerAddressVersion = $customerAddressVersion;

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
     * @param ArrayCollection $subscriptions
     * @return $this
     */
    public function setSubscriptions($subscriptions)
    {
        $this->subscriptions = $subscriptions;

        return $this;
    }

    /**
     * @return BookEntry
     */
    public function getBookEntry()
    {
        return $this->bookEntry;
    }

    /**
     * @param BookEntry $bookEntry
     * @return $this
     */
    public function setBookEntry(BookEntry $bookEntry = null)
    {
        $this->bookEntry = $bookEntry;

        return $this;
    }

    /**
     * @return float
     */
    public function getTotalPrice()
    {
        return $this->totalPrice;
    }

    /**
     * @param float $totalPrice
     *
     * @return $this
     */
    public function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;
        return $this;
    }
}
