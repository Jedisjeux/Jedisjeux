<?php

declare(strict_types=1);

namespace App\Tests\Behat\Bridge\Crud;

use App\Tests\Behat\Bridge\Service\Accessor\TableAccessorInterface;
use Behat\Mink\Element\NodeElement;
use Behat\Mink\Exception\ElementNotFoundException;
use Behat\Mink\Session;
use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;
use Symfony\Component\Routing\RouterInterface;
use Webmozart\Assert\Assert;

abstract class AbstractIndexPage extends SymfonyPage implements IndexPageInterface
{
    public function __construct(
        Session $session,
        \ArrayAccess $minkParameters,
        RouterInterface $router,
        private TableAccessorInterface $tableAccessor
    ) {
        parent::__construct($session, $minkParameters, $router);
    }

    public function isSingleResourceOnPage(array $parameters): bool
    {
        try {
            return 1 === count($this->tableAccessor->getRowsWithFields($this->getElement('table'), $parameters));
        } catch (\InvalidArgumentException | ElementNotFoundException) {
            return false;
        }
    }

    public function getColumnFields(string $columnName): array
    {
        return $this->tableAccessor->getIndexedColumn($this->getElement('table'), $columnName);
    }

    public function sortBy(string $fieldName): void
    {
        $headers = $this->tableAccessor->getSortableHeaders($this->getElement('table'));
        Assert::keyExists($headers, $fieldName, sprintf('Column "%s" is not sortable.', $fieldName));
        $headers[$fieldName]->find('css', 'a')->click();
    }

    public function isSingleResourceWithSpecificElementOnPage(array $parameters, string $element): bool
    {
        try {
            $rows = $this->tableAccessor->getRowsWithFields($this->getElement('table'), $parameters);

            return 1 === count($rows) && null !== $rows[0]->find('css', $element);
        } catch (\InvalidArgumentException | ElementNotFoundException) {
            return false;
        }
    }

    public function countItems(): int
    {
        try {
            return $this->tableAccessor->countTableBodyRows($this->getElement('table'));
        } catch (ElementNotFoundException) {
            return 0;
        }
    }

    public function deleteResourceOnPage(array $parameters): void
    {
        $table = $this->getElement('table');
        $row = $this->tableAccessor->getRowWithFields($table, $parameters);
        $this->tableAccessor->getFieldFromRow($table, $row, 'actions')->pressButton('Delete');
    }

    public function getActionsForResource(array $parameters): NodeElement
    {
        $table = $this->getElement('table');
        $row = $this->tableAccessor->getRowWithFields($table, $parameters);

        return $this->tableAccessor->getFieldFromRow($table, $row, 'actions');
    }

    public function checkResourceOnPage(array $parameters): void
    {
        $table = $this->getElement('table');
        $row = $this->tableAccessor->getRowWithFields($table, $parameters);
        $checkbox = $row->find('css', 'input[data-check-all-group="index"]');
        Assert::notNull($checkbox);
        $checkbox->check();
    }

    public function filter(): void
    {
        $this->getElement('filter')->press();
    }

    public function bulkDelete(): void
    {
        $this->getElement('bulk_actions')->pressButton('Delete');
        $this->getDocument()->waitFor(5, fn () => $this->getElement('confirmation_button')->isVisible());
        $this->getElement('confirmation_button')->press();
    }

    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'bulk_actions' => '.sylius-grid-nav__bulk',
            'confirmation_button' => '#delete-modal-bulk_delete button[type="submit"]',
            'filter' => 'button:contains("Filter")',
            'table' => '.table',
        ]);
    }
}
