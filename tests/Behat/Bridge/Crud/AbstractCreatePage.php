<?php

declare(strict_types=1);

namespace App\Tests\Behat\Bridge\Crud;

use Behat\Mink\Element\NodeElement;
use Behat\Mink\Exception\ElementNotFoundException;
use Behat\Mink\Session;
use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;
use Symfony\Component\Routing\RouterInterface;

abstract class AbstractCreatePage extends SymfonyPage implements CreatePageInterface
{
    public function __construct(Session $session, \ArrayAccess $minkParameters, RouterInterface $router)
    {
        parent::__construct($session, $minkParameters, $router);
    }

    public function create(): void
    {
        $this->getDocument()->pressButton('Create');
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

    private function getFieldElement(string $element): NodeElement
    {
        $element = $this->getElement($element);
        while (!$element->hasClass('field')) {
            $element = $element->getParent();
        }

        return $element;
    }
}
