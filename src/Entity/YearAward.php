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
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Resource\Metadata\AsResource;
use Sylius\Resource\Metadata\Delete;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\Update;

#[ORM\Entity]
#[ORM\Table('jdj_year_award')]
#[AsResource(
    alias: 'app.year_award',
    section: 'backend',
    templatesDir: '@SyliusAdminUi/crud',
    routePrefix: '/admin',
    vars: ['subheader' => 'app.ui.manage_year_awards'],
    operations: [
        new Index(grid: 'app_backend_year_award', vars: ['icon' => 'calendar alternate outline']),
        new Update(redirectToRoute: 'app_backend_year_award_update'),
        new Delete(),
    ],
)]
#[AsResource(
    alias: 'app.year_award',
    section: 'frontend',
    operations: [
        new Index(
            path: '/jeux-primes/',
            template: 'frontend/year_award/index.html.twig',
            grid: 'app_frontend_year_award',
        ),
        new Index(
            shortName: 'index_by_award',
            path: '/jeux-primes/{slug}',
            template: 'frontend/year_award/indexByAward.html.twig',
            grid: 'app_frontend_year_award',
        ),
    ],
)]
class YearAward implements ResourceInterface
{
    use IdentifiableTrait;

    /**
     * @var string|null
     */
    #[ORM\Column(type: 'string')]
    private ?string $year = null;

    /**
     * @var GameAward|null
     */
    #[ORM\ManyToOne(targetEntity: GameAward::class)]
    private ?GameAward $award = null;

    /**
     * @var ProductInterface|null
     */
    #[ORM\ManyToOne(targetEntity: ProductInterface::class, inversedBy: 'yearAwards')]
    private ?ProductInterface $product = null;

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(?string $year): void
    {
        $this->year = $year;
    }

    public function getAward(): ?GameAward
    {
        return $this->award;
    }

    public function setAward(?GameAward $award): void
    {
        $this->award = $award;
    }

    public function getProduct(): ?ProductInterface
    {
        return $this->product;
    }

    public function setProduct(?ProductInterface $product): void
    {
        $this->product = $product;
    }

    public function getName(): ?string
    {
        if (null === $this->getAward()) {
            return null;
        }

        return trim($this->getAward()->getName().' '.$this->getYear());
    }
}
