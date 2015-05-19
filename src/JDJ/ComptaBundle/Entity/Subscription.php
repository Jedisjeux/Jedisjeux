<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 18/05/2015
 * Time: 15:53
 */

namespace JDJ\ComptaBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Subscription
 *
 * @ORM\Entity
 * @ORM\Table(name="cpta_subscription")
 */
class Subscription 
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
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $startAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $endAt;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $toBeRenewed;

    /**
     * @var Customer
     *
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy="bills", cascade={"persist", "merge"})
     */
    private $customer;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="Product", cascade={"persist", "merge"})
     */
    private $product;
}