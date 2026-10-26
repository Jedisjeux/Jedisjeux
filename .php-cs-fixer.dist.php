<?php

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        'array_syntax' => ['syntax' => 'short'],
        // Rules added or hardened in PHP-CS-Fixer 3, disabled to avoid reformatting the whole codebase
        'blank_line_after_opening_tag' => false,
        'fully_qualified_strict_types' => false,
        'modifier_keywords' => false,
        'no_empty_phpdoc' => false,
        'no_superfluous_phpdoc_tags' => false,
        'nullable_type_declaration_for_default_null_value' => false,
        'phpdoc_separation' => false,
        'statement_indentation' => false,
        'trailing_comma_in_multiline' => false,
    ])
;
