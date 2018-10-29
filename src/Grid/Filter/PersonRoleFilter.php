<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Grid\Filter;

use Sylius\Component\Grid\Data\DataSourceInterface;
use Sylius\Component\Grid\Filtering\FilterInterface;

class PersonRoleFilter implements FilterInterface
{
    /**
     * {@inheritdoc}
     */
    public function apply(DataSourceInterface $dataSource, string $name, $data, array $options = []): void
    {
        if (empty($data)) {
            return;
        }

        $field = null;

        switch ($data) {
            case 'designers':
                $field = 'productCountAsDesigner';
                break;
            case 'artists':
                $field = 'productCountAsArtist';
                break;
            case 'publishers':
                $field = 'productCountAsPublisher';
                break;
        }

        if (null == $field) {
            return;
        }

        $dataSource->restrict($dataSource->getExpressionBuilder()->greaterThan($field, 0));
    }
}
