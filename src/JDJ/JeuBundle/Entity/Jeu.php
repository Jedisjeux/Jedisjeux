<?php

namespace JDJ\JeuBundle\Entity;

use AppBundle\Model\Identifiable;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * Jeu
 *
 * @ORM\Entity(repositoryClass="JDJ\JeuBundle\Repository\JeuRepository")
 * @ORM\Table(name="jdj_jeu")
 */
class Jeu implements ResourceInterface
{
    use Identifiable,
        TimestampableEntity;

    /**
     * status constants
     */
    const WRITING = "WRITING";
    const NEED_A_TRANSLATION = "NEED_A_TRANSLATION";
    const NEED_A_REVIEW = "NEED_A_REVIEW";
    const READY_TO_PUBLISH = "READY_TO_PUBLISH";
    const PUBLISHED = "PUBLISHED";
}
