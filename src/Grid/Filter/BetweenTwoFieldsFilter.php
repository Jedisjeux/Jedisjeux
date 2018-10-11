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
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class BetweenTwoFieldsFilter implements FilterInterface
{
    /**
     * {@inheritdoc}
     */
    public function apply(DataSourceInterface $dataSource, string $name, $data, array $options = []): void
    {
        if (empty($data['value'])) {
            return;
        }

        $firstField = $this->getField('first', $options);
        $secondField = $this->getField('second', $options);

        $dataSource->restrict($dataSource->getExpressionBuilder()->lessThanOrEqual($firstField, $data['value']));
        $dataSource->restrict($dataSource->getExpressionBuilder()->greaterThanOrEqual($secondField, $data['value']));
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return string
     */
    private function getField(string $name, array $options): string
    {
        $fields = $this->getFields($options);

        if (!isset($fields[$name])) {
            throw new MissingOptionsException(sprintf('Field %s is missing', $name));
        }

        return $fields[$name];
    }

    /**
     * @param array $options
     *
     * @return array
     */
    private function getFields(array $options): array
    {
        if (!isset($options['fields'])) {
            throw new MissingOptionsException('Option fields is missing');
        }

        return $options['fields'];
    }
}
