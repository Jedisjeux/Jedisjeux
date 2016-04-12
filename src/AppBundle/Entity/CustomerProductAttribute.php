<?php

namespace AppBundle\Entity;

use AppBundle\Model\Identifiable;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\User\Model\CustomerInterface;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_customer_product_attribute")
 */
class CustomerProductAttribute
{
    use Identifiable;

    const ATTRIBUTE_FAVORITE = "favorite";

    const ATTRIBUTE_OWNED = "owned";

    const ATTRIBUTE_PLAYED = "played";

    const ATTRIBUTE_WANTED = "wanted";

    /**
     * @var CustomerInterface
     *
     * @ORM\ManyToOne(targetEntity="Sylius\Component\User\Model\CustomerInterface")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $customer;

    /**
     * @var ProductInterface
     *
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Product\Model\ProductInterface")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $product;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $attribute;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $value;

    /**
     * @return CustomerInterface
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param CustomerInterface $customer
     *
     * @return $this
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return ProductInterface
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param ProductInterface $product
     *
     * @return $this
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return string
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * @param string $attribute
     *
     * @return $this
     */
    public function setAttribute($attribute)
    {
        $this->attribute = $attribute;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isValue()
    {
        return $this->value;
    }

    /**
     * @param boolean $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }
}
