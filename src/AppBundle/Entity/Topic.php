<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 17/02/2016
 * Time: 13:35
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Model\Identifiable;
use Knp\DoctrineBehaviors\Model\Blameable\Blameable;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 *
 * @ORM\Entity
 */
class Topic implements ResourceInterface
{
    use Identifiable,
        Blameable;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    protected $title;

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function __toString()
    {
        return $this->getTitle();
    }
}
