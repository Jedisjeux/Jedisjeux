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

class GreaterThanOrEqualFilter implements FilterInterface
{
    /**
     * {@inheritdoc}
     */
    public function apply(DataSourceInterface $dataSource, string $name, $data, array $options = []): void
    {
        if (empty($data['value'])) {
            return;
        }

        $field = (string) $this->getOption($options, 'field', $name);
        $dataSource->restrict($dataSource->getExpressionBuilder()->greaterThanOrEqual($field, $data['value']));
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
