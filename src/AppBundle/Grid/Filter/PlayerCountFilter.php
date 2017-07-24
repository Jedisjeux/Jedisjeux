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
class PlayerCountFilter implements FilterInterface
{
    /**
     * {@inheritdoc}
     */
    public function apply(DataSourceInterface $dataSource, $name, $data, array $options = [])
    {
        $dataSource->restrict($dataSource->getExpressionBuilder()->lessThanOrEqual('product.joueurMin', $data['value']));
        $dataSource->restrict($dataSource->getExpressionBuilder()->greaterThanOrEqual('product.joueurMax', $data['value']));
    }
}
