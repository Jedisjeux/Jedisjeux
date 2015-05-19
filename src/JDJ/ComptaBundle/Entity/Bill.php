<?php

namespace JDJ\ComptaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Bill
 *
 * @ORM\Entity
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
     * @var float
     *
     * @ORM\Column(type="decimal", precision=6, scale=2, nullable=false)
     */
    private $totalPrice;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
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
     */
    private $customer;
}
