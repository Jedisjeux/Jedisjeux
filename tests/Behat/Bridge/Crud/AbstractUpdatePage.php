<?php

declare(strict_types=1);

namespace App\Tests\Behat\Bridge\Crud;

use App\Formatter\StringInflector;
use Behat\Mink\Element\NodeElement;
use Behat\Mink\Exception\ElementNotFoundException;
use Behat\Mink\Session;
use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;
use Symfony\Component\Routing\RouterInterface;

abstract class AbstractUpdatePage extends SymfonyPage implements UpdatePageInterface
{
    public function __construct(Session $session, \ArrayAccess $minkParameters, RouterInterface $router)
    {
        parent::__construct($session, $minkParameters, $router);
    }

    public function saveChanges(): void
    {
        $this->getDocument()->pressButton('sylius_save_changes_button');
    }

    public function getValidationMessage(string $element): string
    {
        $field = $this->getFieldElement($element);
        $message = $field->find('css', '.sylius-validation-error');
        if (null === $message) {
            throw new ElementNotFoundException($this->getSession(), 'Validation message', 'css', '.sylius-validation-error');
        }

        return $message->getText();
    }

    public function hasResourceValues(array $parameters): bool
    {
        foreach ($parameters as $element => $value) {
            if ($this->getElement($element)->getValue() !== (string) $value) {
                return false;
            }
        }

        return true;
    }

    private function getFieldElement(string $element): NodeElement
    {
        $element = $this->getElement(StringInflector::nameToCode($element));
        while (!$element->hasClass('field')) {
            $element = $element->getParent();
        }

        return $element;
    }
}
