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

namespace App\Twig\Components\Taxon;

use App\Repository\TaxonRepository;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

/**
 * Children of a root taxon, listed in the menus and the sidebars.
 * Each subclass renders them with its own template.
 */
abstract class Children
{
    /** Code of the root taxon */
    public string $code;

    /** Slug of the taxon to highlight */
    public ?string $currentSlug = null;

    public function __construct(
        #[Autowire(service: 'sylius.repository.taxon')]
        private readonly TaxonRepository $taxonRepository,
        #[Autowire(service: 'sylius.context.locale')]
        private readonly LocaleContextInterface $localeContext,
        private readonly Security $security,
    ) {
    }

    /**
     * @return TaxonInterface[]
     */
    #[ExposeInTemplate]
    public function getTaxons(): array
    {
        return $this->taxonRepository->findChildrenAsTreeByRootCode($this->code, $this->security->isGranted('ROLE_STAFF'));
    }

    #[ExposeInTemplate]
    public function getCurrent(): ?TaxonInterface
    {
        if (null === $this->currentSlug) {
            return null;
        }

        $current = $this->taxonRepository->findOneBySlug($this->currentSlug, $this->localeContext->getLocaleCode());

        if (null === $current) {
            throw new NotFoundHttpException(sprintf('Taxon "%s" does not exist.', $this->currentSlug));
        }

        return $current;
    }
}
