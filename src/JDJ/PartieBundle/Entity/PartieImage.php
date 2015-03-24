<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 24/03/15
 * Time: 12:49
 */

namespace JDJ\PartieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JDJ\CoreBundle\Entity\Image;

/**
 * Class PartieImage
 * @package JDJ\PartieBundle\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_partie_image")
 */
class PartieImage
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
     * @var Partie
     *
     * @ORM\ManyToOne(targetEntity="Partie", inversedBy="partieImages")
     */
    private $partie;

    /**
     * @var Image
     *
     * @ORM\ManyToOne(targetEntity="JDJ\CoreBundle\Entity\Image", cascade={"persist"})
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return Image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param Image $image
     * @return $this
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Partie
     */
    public function getPartie()
    {
        return $this->partie;
    }

    /**
     * @param Partie $partie
     * @return $this
     */
    public function setPartie($partie)
    {
        $this->partie = $partie;

        return $this;
    }



} 