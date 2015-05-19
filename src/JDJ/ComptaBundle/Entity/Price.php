<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 19/05/2015
 * Time: 14:14
 */

namespace JDJ\ComptaBundle\Entity;

/**
 * Price
 *
 * @ORM\Entity
 * @ORM\Table(name="cpta_price")
 */
class Price
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
    private $value;

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
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $endAt;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="Product")
     */
    private $product;
}