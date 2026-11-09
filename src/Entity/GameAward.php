<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Resource\Metadata\AsResource;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Delete;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\Update;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table('jdj_game_award')]
#[AsResource(
    alias: 'app.game_award',
    section: 'backend',
    templatesDir: '@SyliusAdminUi/crud',
    routePrefix: '/admin',
    vars: ['subheader' => 'app.ui.manage_game_awards'],
    operations: [
        new Index(grid: 'app_backend_game_award', vars: ['icon' => 'trophy']),
        new Create(redirectToRoute: 'app_backend_game_award_update'),
        new Update(redirectToRoute: 'app_backend_game_award_update'),
        new Delete(),
    ],
)]
class GameAward implements ResourceInterface
{
    use IdentifiableTrait;

    /**
     * @var string|null
     */
    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank]
    private ?string $name = null;

    /**
     * @var string|null
     */
    #[Gedmo\Slug(fields: ['name'])]
    #[ORM\Column(type: 'string', unique: true, nullable: true)]
    private ?string $slug = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

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
     */
    #[ORM\OneToOne(targetEntity: GameAwardImage::class, cascade: ['persist'])]
    #[Assert\Valid]
    private ?GameAwardImage $image = null;

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
