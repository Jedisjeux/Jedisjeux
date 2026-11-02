<?php

declare(strict_types=1);

namespace App\Tests\Behat\Bridge\Service\Accessor;

use Behat\Mink\Element\NodeElement;
use Webmozart\Assert\Assert;

final class TableAccessor implements TableAccessorInterface
{
    public function getRowWithFields(NodeElement $table, array $fields): NodeElement
    {
        try {
            return $this->getRowsWithFields($table, $fields)[0];
        } catch (\InvalidArgumentException $exception) {
            throw new \InvalidArgumentException('Could not find row with given fields', 0, $exception);
        }
    }

    public function getRowsWithFields(NodeElement $table, array $fields): array
    {
        try {
            return $this->findRowsWithFields($table, $fields);
        } catch (\InvalidArgumentException $exception) {
            throw new \InvalidArgumentException('Could not find any row with given fields', 0, $exception);
        }
    }

    public function getFieldFromRow(NodeElement $table, NodeElement $row, $field): NodeElement
    {
        $columnIndex = $this->getColumnIndex($table, $field);
        $columns = $row->findAll('css', 'td,th');
        if (!isset($columns[$columnIndex])) {
            throw new \InvalidArgumentException(sprintf('Could not find column with index %d', $columnIndex));
        }

        return $columns[$columnIndex];
    }

    public function getIndexedColumn(NodeElement $table, $fieldName): array
    {
        $columnIndex = $this->getColumnIndex($table, $fieldName);
        $rows = $table->findAll('css', 'tbody > tr');
        Assert::notEmpty($rows, 'There are no rows!');
        $values = [];
        foreach ($rows as $row) {
            $cells = $row->findAll('css', 'td');
            $values[] = $cells[$columnIndex]->getText();
        }

        return $values;
    }

    public function getSortableHeaders(NodeElement $table): array
    {
        $headers = $table->findAll('css', 'th.sortable');
        Assert::notEmpty($headers, 'There are no sortable headers.');
        $sortableHeaders = [];
        foreach ($headers as $header) {
            $sortableHeaders[$this->getColumnFieldName($header)] = $header;
        }

        return $sortableHeaders;
    }

    public function countTableBodyRows(NodeElement $table): int
    {
        return count($table->findAll('css', 'tbody > tr'));
    }

    private function findRowsWithFields(NodeElement $table, array $fields): array
    {
        $rows = $table->findAll('css', 'tr');
        Assert::notEmpty($rows, 'There are no rows!');
        $fields = $this->replaceColumnNamesWithColumnIndexes($table, $fields);
        $matchedRows = [];
        foreach ($rows as $row) {
            if ($this->hasRowFields($row->findAll('css', 'td, th'), $fields)) {
                $matchedRows[] = $row;
            }
        }

        return $matchedRows;
    }

    private function hasRowFields(array $columns, array $fields): bool
    {
        foreach ($fields as $index => $searchedValue) {
            if (!isset($columns[$index])) {
                return false;
            }

            $searchedValue = trim((string) $searchedValue);
            if (str_starts_with($searchedValue, '%') && str_ends_with($searchedValue, '%')) {
                $searchedValue = substr($searchedValue, 1, -1);
            }

            if (false === stripos(trim($columns[$index]->getText()), $searchedValue)) {
                return false;
            }
        }

        return true;
    }

    private function replaceColumnNamesWithColumnIndexes(NodeElement $table, array $fields): array
    {
        $replacedFields = [];
        foreach ($fields as $columnName => $expectedValue) {
            $replacedFields[$this->getColumnIndex($table, $columnName)] = $expectedValue;
        }

        return $replacedFields;
    }

    private function getColumnIndex(NodeElement $table, string $fieldName): int
    {
        $rows = $table->findAll('css', 'tr');
        Assert::notEmpty($rows, 'There are no rows!');
        foreach ($rows[0]->findAll('css', 'th,td') as $index => $column) {
            if ($fieldName === $this->getColumnFieldName($column)) {
                return $index;
            }
        }

        throw new \InvalidArgumentException(sprintf('Column with name "%s" not found!', $fieldName));
    }

    private function getColumnFieldName(NodeElement $column): string
    {
        preg_match('/.*sylius-table-column-([^ ]+).*$/', (string) $column->getAttribute('class'), $matches);

        return $matches[1] ?? '';
    }
}
