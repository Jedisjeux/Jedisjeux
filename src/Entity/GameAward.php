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
use Gedmo\Mapping\Annotation as Gedmo;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table("jdj_game_award")
 */
class GameAward implements ResourceInterface
{
    use IdentifiableTrait;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string|null
     *
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
    private $slug;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @var GameAwardImage|null
     *
     * @ORM\OneToOne(targetEntity="App\Entity\GameAwardImage", cascade={"persist"})
     *
     * @Assert\Valid()
     */
    private $image;

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): void
    {
        $this->slug = $slug;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getImage(): ?GameAwardImage
    {
        return $this->image;
    }

    public function setImage(?GameAwardImage $image): void
    {
        $this->image = $image;
    }
}
