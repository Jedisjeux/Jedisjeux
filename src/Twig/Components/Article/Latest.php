<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Twig\Components\Article;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

/**
 * Latest published articles, optionally restricted to a category and its subcategories.
 */
#[AsTwigComponent(name: 'Article:Latest', template: 'frontend/article/_magazineList.html.twig')]
class Latest
{
    public int $count = 4;

    public ?TaxonInterface $taxon = null;

    public function __construct(
        #[Autowire(service: 'app.repository.article')]
        private readonly ArticleRepository $articleRepository,
        #[Autowire(service: 'sylius.context.locale')]
        private readonly LocaleContextInterface $localeContext,
    ) {
    }

    /**
     * @return Article[]
     */
    #[ExposeInTemplate]
    public function getArticles(): array
    {
        $queryBuilder = $this->articleRepository
            ->createFrontendListQueryBuilder($this->localeContext->getLocaleCode())
            ->addOrderBy('o.publishStartDate', 'DESC')
            ->setMaxResults($this->count)
        ;

        if (null !== $this->taxon) {
            $queryBuilder
                ->andWhere('mainTaxon.left >= :taxonLeft')
                ->andWhere('mainTaxon.right <= :taxonRight')
                ->andWhere('mainTaxon.root = :taxonRoot')
                ->setParameter('taxonLeft', $this->taxon->getLeft())
                ->setParameter('taxonRight', $this->taxon->getRight())
                ->setParameter('taxonRoot', $this->taxon->getRoot())
            ;
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
