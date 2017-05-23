<?php

/*
 * This file is part of Alceane.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Grid\Filter;

use Sylius\Component\Grid\Data\DataSourceInterface;
use Sylius\Component\Grid\Filtering\FilterInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class StatusFilter implements FilterInterface
{
    /**
     * {@inheritdoc}
     */
    public function apply(DataSourceInterface $dataSource, $name, $data, array $options = array())
    {
        // Your filtering logic. DataSource is kind of query builder.
        // $data['category'] contains the submitted value!

        if (empty($data['status'])) {
            return null;
        }

        $dataSource->restrict($dataSource->getExpressionBuilder()->equals('status', $data['status']));
    }
}
