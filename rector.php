<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/src',
    ])
    ->withImportNames(importShortClasses: false, removeUnusedImports: true)
    ->withPreparedSets(typeDeclarations: true)
    ->withConfiguredRule(ClassPropertyAssignToConstructorPromotionRector::class, [
        'inline_public' => true,
        // Symfony binds arguments by their name, renaming them breaks the autowiring
        'rename_property' => false,
    ])
;
