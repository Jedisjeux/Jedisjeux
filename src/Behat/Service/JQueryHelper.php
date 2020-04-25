<?php

declare(strict_types=1);

namespace App\Behat\Service;

use Behat\Mink\Element\DocumentElement;
use Behat\Mink\Session;

abstract class JQueryHelper
{
    public static function waitForAsynchronousActionsToFinish(Session $session): void
    {
        $session->wait(5000, '0 === jQuery.active');
    }

    public static function waitForFormToStopLoading(DocumentElement $document, int $timeout = 10): void
    {
        $form = $document->find('css', 'form');
        $document->waitFor($timeout, function () use ($form) {
            return !$form->hasClass('loading');
        });
    }
}
