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

use App\Entity\Taxon;
use App\Repository\TaxonRepository;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Resource\Twig\Context\Factory\ContextFactoryInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Adds the forum category of the URL slug, which only the staff can see when it is private.
 */
final class ForumTaxonContextFactory extends TaxonContextFactory
{
    public function __construct(
        ContextFactoryInterface $decorated,
        #[Autowire(service: 'sylius.repository.taxon')]
        TaxonRepository $taxonRepository,
        #[Autowire('%locale%')]
        string $locale,
        private readonly Security $security,
    ) {
        parent::__construct($decorated, $taxonRepository, $locale);
    }

    protected function checkAccess(TaxonInterface $taxon): void
    {
        if ($taxon instanceof Taxon && !$taxon->isPublic() && !$this->security->isGranted('ROLE_STAFF')) {
            throw new AccessDeniedException();
        }
    }
}
