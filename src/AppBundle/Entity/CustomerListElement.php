<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 12/04/2016
 * Time: 13:13
 */

namespace AppBundle\Entity;

use AppBundle\Model\Identifiable;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Sylius\Component\Resource\Model\ResourceInterface;


/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_customer_list_element")
 */
class CustomerListElement implements ResourceInterface
{
    use Identifiable,
        Timestampable;

    /**
     * @var CustomerList
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\CustomerList")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $customerList;

    /**
     * @var string $objectId
     *
     * @ORM\Column(name="object_id", length=64, nullable=false)
     */
    protected $objectId;

    /**
     * @var string $objectClass
     *
     * @ORM\Column(name="object_class", type="string", length=255)
     */
    protected $objectClass;

    /**
     * @var ResourceInterface
     */
    protected $element;

    /**
     * @return CustomerList
     */
    public function getCustomerList()
    {
        return $this->customerList;
    }

    /**
     * @param CustomerList $customerList
     */
    public function setCustomerList($customerList)
    {
        $this->customerList = $customerList;
    }

    /**
     * @return string
     */
    public function getObjectId()
    {
        return $this->objectId;
    }

    /**
     * @param string $objectId
     *
     * @return $this
     */
    public function setObjectId($objectId)
    {
        $this->objectId = $objectId;

        return $this;
    }

    /**
     * @return string
     */
    public function getObjectClass()
    {
        return $this->objectClass;
    }

    /**
     * @param string $objectClass
     *
     * @return $this
     */
    public function setObjectClass($objectClass)
    {
        $this->objectClass = $objectClass;

        return $this;
    }

    /**
     * @return ResourceInterface
     */
    public function getElement()
    {
        return $this->element;
    }

    /**
     * @param ResourceInterface $element
     *
     * @return $this
     */
    public function setElement($element)
    {
        $this->element = $element;

        return $this;
    }
}