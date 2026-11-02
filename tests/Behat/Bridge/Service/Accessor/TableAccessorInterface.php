<?php

declare(strict_types=1);

namespace App\Tests\Behat\Bridge\Service\Accessor;

use Behat\Mink\Element\NodeElement;

interface TableAccessorInterface
{
    public function getRowWithFields(NodeElement $table, array $fields);

    public function getRowsWithFields(NodeElement $table, array $fields);

    public function getIndexedColumn(NodeElement $table, $fieldName);

    public function getSortableHeaders(NodeElement $table);

    public function getFieldFromRow(NodeElement $table, NodeElement $row, $field);

    public function countTableBodyRows(NodeElement $table);
}
