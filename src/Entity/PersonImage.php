<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Person", inversedBy="images")
     */
    protected $person;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $description;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", name="is_main")
     */
    protected $main = false;

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function setPerson(?Person $person): void
    {
        $this->person = $person;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function isMain(): bool
    {
        return $this->main;
    }

    public function setMain(bool $main): void
    {
        $this->main = $main;
    }
}
