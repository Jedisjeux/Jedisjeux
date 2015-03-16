<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 16/03/15
 * Time: 13:25
 */

namespace JDJ\JeuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class JeuImage
 *
 * @package JDJ\JeuBundle\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_jeu_image")
 */
class JeuImage
{
    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="JDJ\JeuBundle\Entity\Jeu", inversedBy="jeuImages")
     * @ORM\Id
     */
    private $jeu;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="JDJ\CoreBundle\Entity\Image")
     * @ORM\Id
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
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     * @return $this
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return string
     */
    public function getJeu()
    {
        return $this->jeu;
    }

    /**
     * @param string $jeu
     * @return $this
     */
    public function setJeu($jeu)
    {
        $this->jeu = $jeu;

        return $this;
    }


} 