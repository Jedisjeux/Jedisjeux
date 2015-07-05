<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 26/05/2015
 * Time: 13:16
 */

namespace JDJ\ComptaBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 *
 * @ORM\Entity(repositoryClass="JDJ\ComptaBundle\Entity\Repository\AddressRepository")
 * @ORM\Table(name="cpta_address")
 * @Gedmo\Loggable
 */
class Address 
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
     * @ORM\Column(type="string")
     */
    private $street;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(type="string", nullable=true)
     */
    private $additionalAddressInfo;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(type="string")
     */
    private $postalCode;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(type="string")
     */
    private $city;

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
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param string $street
     * @return $this
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * @return string
     */
    public function getAdditionalAddressInfo()
    {
        return $this->additionalAddressInfo;
    }

    /**
     * @param string $additionalAdressInfo
     * @return $this
     */
    public function setAdditionalAddressInfo($additionalAdressInfo)
    {
        $this->additionalAddressInfo = $additionalAdressInfo;

        return $this;
    }

    /**
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @param string $postalCode
     * @return $this
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return $this
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }
}