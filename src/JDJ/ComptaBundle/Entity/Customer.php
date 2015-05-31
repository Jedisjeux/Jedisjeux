<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 19/05/2015
 * Time: 13:44
 */

namespace JDJ\ComptaBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Customer
 *
 * @ORM\Entity
 * @ORM\Table(name="cpta_customer")
 */
class Customer 
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
     * @ORM\Column(type="string")
     */
    private $companyName;

    /**
     * @var Address
     *
     * @ORM\OneToOne(targetEntity="Address", cascade={"persist", "merge"})
     */
    private $address;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Bill", mappedBy="customer", cascade={"persist", "merge"})
     */
    private $bills;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->bills = new ArrayCollection();
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
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * @param string $companyName
     * @return $this
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;

        return $this;
    }

    /**
     * @return Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param Address $address
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getBills()
    {
        return $this->bills;
    }

    /**
     * @param ArrayCollection $bills
     * @return $this
     */
    public function setBills($bills)
    {
        $this->bills = $bills;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getCompanyName();
    }
}