<?php

namespace JDJ\JediZoneBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class Notification
 * @package JDJ\JediZoneBundle\Entity
 *
 * @ORM\Entity(repositoryClass="JDJ\JediZoneBundle\Repository\NotificationRepository")
 * @ORM\Table(name="jdj_notification")
 *
 */
class Notification
{
    const ACTION_ACCEPT = 'accept';
    const ACTION_DECLINE = 'decline';

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
