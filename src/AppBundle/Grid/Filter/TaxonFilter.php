<?php

/**
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Grid\Filter;

use Doctrine\ORM\EntityRepository;
use Sylius\Component\Grid\Data\DataSourceInterface;
use Sylius\Component\Grid\Filtering\FilterInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Webmozart\Assert\Assert;


/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class TaxonFilter implements FilterInterface
{
    /**
     * @var EntityRepository
     */
    private $taxonRepository;

    /**
     * TaxonFilter constructor.
     * @param EntityRepository $taxonRepository
     */
    public function __construct(EntityRepository $taxonRepository)
    {
        $this->taxonRepository = $taxonRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(DataSourceInterface $dataSource, string $name, $data, array $options = array()): void
    {
        // Your filtering logic. DataSource is kind of query builder.
        // $data['mainTaxon'] contains the submitted value!

        if (empty($data['mainTaxon'])) {
            return;
        }

        /** @var TaxonInterface $taxon */
        $taxon = $this->taxonRepository->find($data['mainTaxon']);
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
     * @param array $options
     * @param string $name
     * @param mixed $default
     *
     * @return mixed
     */
    private function getOption(array $options, string $name, $default)
    {
        return $options[$name] ?? $default;
    }
}
