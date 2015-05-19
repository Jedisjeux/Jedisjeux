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
}