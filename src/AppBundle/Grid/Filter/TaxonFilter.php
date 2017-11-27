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

use Sylius\Component\Grid\Data\DataSourceInterface;
use Sylius\Component\Grid\Filtering\FilterInterface;


/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class TaxonFilter implements FilterInterface
{
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

        $dataSource->restrict($dataSource->getExpressionBuilder()->equals('mainTaxon.code', $data['mainTaxon']));
    }
}
