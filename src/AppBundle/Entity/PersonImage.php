<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 29/03/2016
 * Time: 17:04
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_person_image")
 */
class PersonImage extends AbstractImage
{
    /**
     * @var Person
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Person", inversedBy="images")
     */
    protected $person;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $description;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", name="is_main")
     */
    protected $main = false;

    /**
     * @return Person|null
     */
    public function getPerson(): ?Person
    {
        return $this->person;
    }

    /**
     * @param Person|null $person
     */
    public function setPerson(?Person $person): void
    {
        $this->person = $person;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return boolean
     */
    public function isMain(): bool
    {
        return $this->main;
    }

    /**
     * @param boolean $main
     */
    public function setMain(bool $main): void
    {
        $this->main = $main;
    }
}
