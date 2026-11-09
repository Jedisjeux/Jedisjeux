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

namespace App\Twig\Context;

use App\Repository\TaxonRepository;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\Twig\Context\Factory\ContextFactoryInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Adds the category of the URL slug to the templates of the pages listing a category.
 */
class TaxonContextFactory implements ContextFactoryInterface
{
    public function __construct(
        private readonly ContextFactoryInterface $decorated,
        #[Autowire(service: 'sylius.repository.taxon')]
        private readonly TaxonRepository $taxonRepository,
        #[Autowire('%locale%')]
        private readonly string $locale,
    ) {
    }

    public function create(mixed $data, Operation $operation, Context $context): array
    {
        $slug = (string) $context->get(RequestOption::class)?->request()?->attributes->get('slug');
        $taxon = $this->taxonRepository->findOneBySlug($slug, $this->locale);

        if (null === $taxon) {
            throw new NotFoundHttpException(sprintf('Taxon "%s" does not exist.', $slug));
        }

        $this->checkAccess($taxon);

        return array_merge($this->decorated->create($data, $operation, $context), ['taxon' => $taxon]);
    }

    protected function checkAccess(TaxonInterface $taxon): void
    {
    }
}
