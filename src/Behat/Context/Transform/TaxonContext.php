<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Context\Transform;

use Behat\Behat\Context\Context;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Webmozart\Assert\Assert;

final class TaxonContext implements Context
{
    /**
     * @var TaxonRepositoryInterface
     */
    private $taxonRepository;

    /**
     * @param TaxonRepositoryInterface $taxonRepository
     */
    public function __construct(TaxonRepositoryInterface $taxonRepository)
    {
        $this->taxonRepository = $taxonRepository;
    }

    /**
     * @Transform /^"([^"]+)" (taxon|mechanism|theme|category)$/
     * @Transform /^taxon with "([^"]+)" name/
     * @Transform /^taxon "([^"]+)"$/
     * @Transform /^category "([^"]+)"$/
     * @Transform :taxon
     * @Transform :mechanism
     */
    public function getTaxonByName($name)
    {
        $taxons = $this->taxonRepository->findByName($name, 'en_US');

        Assert::eq(
            count($taxons),
            1,
            sprintf('%d taxons has been found with name "%s".', count($taxons), $name)
        );

        return $taxons[0];
    }

    /**
     * @Transform /^taxon with "([^"]+)" code$/
     */
    public function getTaxonByCode($code)
    {
        $taxon = $this->taxonRepository->findOneBy(['code' => $code]);
        Assert::notNull($taxon, sprintf('Taxon with code "%s" does not exist.', $code));

        return $taxon;
    }

    /**
     * @Transform /^classified as "([^"]+)" or "([^"]+)"$/
     */
    public function getTaxonsByNames($firstTaxon, $secondTaxon)
    {
        return [
            $this->getTaxonByName($firstTaxon),
            $this->getTaxonByName($secondTaxon),
        ];
    }
}
