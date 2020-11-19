<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Grid\Filter;

use Sylius\Component\Grid\Data\DataSourceInterface;
use Sylius\Component\Grid\Filtering\FilterInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class TaxonFilter implements FilterInterface
{
    /**
     * @var TaxonRepositoryInterface
     */
    private $taxonRepository;

    /**
     * @var string
     */
    private $locale;

    public function __construct(TaxonRepositoryInterface $taxonRepository, string $locale)
    {
        $this->taxonRepository = $taxonRepository;
        $this->locale = $locale;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(DataSourceInterface $dataSource, string $name, $data, array $options = []): void
    {
        // Your filtering logic. DataSource is kind of query builder.
        // $data['mainTaxon'] contains the submitted value!

        if (empty($data['mainTaxon'])) {
            return;
        }

        /** @var TaxonInterface $taxon */
        $taxon = $this->taxonRepository->findOneBySlug($data['mainTaxon'], $this->locale);
        Assert::notNull($taxon);

        $field = (string) $this->getOption($options, 'field', $name);

        $dataSource->restrict(
            $dataSource->getExpressionBuilder()->andX(
                $dataSource->getExpressionBuilder()->greaterThanOrEqual(sprintf('%s.left', $field), $taxon->getLeft()),
                $dataSource->getExpressionBuilder()->lessThanOrEqual(sprintf('%s.right', $field), $taxon->getRight()),
                $dataSource->getExpressionBuilder()->equals(sprintf('%s.root', $field), $taxon->getRoot())
            )
        );
    }

    /**
     * @param mixed $default
     *
     * @return mixed
     */
    private function getOption(array $options, string $name, $default)
    {
        return $options[$name] ?? $default;
    }
}
