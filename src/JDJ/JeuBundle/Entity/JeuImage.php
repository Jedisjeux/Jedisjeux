<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 16/03/15
 * Time: 13:25
 */

namespace JDJ\JeuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JDJ\CoreBundle\Entity\Image;

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
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Jeu
     *
     * @ORM\ManyToOne(targetEntity="JDJ\JeuBundle\Entity\Jeu", inversedBy="jeuImages")
     */
    private $jeu;

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
     * @return Jeu
     */
    public function getJeu()
    {
        return $this->jeu;
    }

    /**
     * @param Jeu $jeu
     * @return $this
     */
    public function setJeu($jeu)
    {
        $this->jeu = $jeu;

        return $this;
    }

    public function upload()
    {
        $this->getImage()->setNewFilename($this->getNewFilename());
        $this->getImage()->upload();
    }

    public function getNewFilename()
    {
        if (null === $this->getImage()->getFile()) {
            return null;
        }

        return $this->getJeu()->getSlug().'-'.uniqid().'.'.$this->getImage()->getFile()->getClientOriginalExtension();
    }
}
