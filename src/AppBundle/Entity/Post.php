<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 18/02/16
 * Time: 08:16
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Model\Identifiable;
use Knp\DoctrineBehaviors\Model\Blameable\Blameable;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 */
class Post implements ResourceInterface
{
    use Identifiable,
        Blameable;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    protected $body;
}