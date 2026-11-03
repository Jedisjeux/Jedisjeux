<?php

declare(strict_types=1);

namespace App\Tests\Behat\Bridge\Service\Resolver;

interface CurrentPageResolverInterface
{
    public function getCurrentPageWithForm(array $pages);
}
