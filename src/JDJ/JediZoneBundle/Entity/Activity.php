<?php

namespace JDJ\JediZoneBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Sylius\Component\Product\Model\ProductInterface;

/**
 * Class Activity
 * @package JDJ\JediZoneBundle\Entity
 *
 * @ORM\Entity(repositoryClass="JDJ\JediZoneBundle\Repository\ActivityRepository")
 * @ORM\Table(name="jdj_activity")
 *
 */
class Activity
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
