<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 26/05/2015
 * Time: 15:37
 */

namespace JDJ\ComptaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * BookEntry
 *
 * @ORM\Entity
 * @ORM\Table(name="cpta_book_entry")
 */
class BookEntry 
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
     * @var float
     *
     * @ORM\Column(type="decimal", precision=6, scale=2)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $label;

    /**
     * @var PaymentMethod
     *
     * @ORM\ManyToOne(targetEntity="PaymentMethod")
     * @ORM\JoinColumn(nullable=false)
     */
    private $paymentMethod;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $creditedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $debitedAt;

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
     * @param boolean $credit
     * @return $this
     */
    public function setCredit($credit)
    {
        $this->credit = $credit;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return $this
     */
    public function setLabel($label)
    {
        $this->label = $label;

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
     * @return \DateTime
     */
    public function getCreditedAt()
    {
        return $this->creditedAt;
    }

    /**
     * @param \DateTime $creditedAt
     * @return $this
     */
    public function setCreditedAt($creditedAt)
    {
        $this->creditedAt = $creditedAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDebitedAt()
    {
        return $this->debitedAt;
    }

    /**
     * @param \DateTime $debitedAt
     * @return $this
     */
    public function setDebitedAt($debitedAt)
    {
        $this->debitedAt = $debitedAt;

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


}