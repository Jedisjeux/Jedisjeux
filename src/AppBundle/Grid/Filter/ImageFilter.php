<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
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
class ImageFilter implements FilterInterface
{
    /**
     * {@inheritdoc}
     */
    public function apply(DataSourceInterface $dataSource, string $name, $data, array $options = array()): void
    {
        // Your filtering logic. DataSource is kind of query builder.
        // $data['value'] contains the submitted value!

        if (empty($data['value'])) {
            return;
        }

        if ('with' === $data['value']) {
            $dataSource->restrict($dataSource->getExpressionBuilder()->greaterThan('imageCount', 0));
        } else {
            $dataSource->restrict($dataSource->getExpressionBuilder()->equals('imageCount', 0));
        }
    }
}
