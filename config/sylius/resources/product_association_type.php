<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

use Sylius\Component\Product\Model\ProductAssociationType;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Delete;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\Operations;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Metadata\Update;

// The model comes from Sylius: its operations cannot be declared with #[AsResource]
return (new ResourceMetadata())
    ->withClass(ProductAssociationType::class)
    ->withAlias('sylius.product_association_type')
    ->withSection('backend')
    ->withTemplatesDir('@SyliusAdminUi/crud')
    ->withRoutePrefix('/admin')
    ->withVars([
        'subheader' => 'sylius.ui.manage_association_types_of_your_products',
        'templates' => ['form' => 'backend/product_association_type/_form.html.twig'],
    ])
    ->withOperations(new Operations([
        new Index(grid: 'sylius_backend_product_association_type', vars: ['icon' => 'tasks']),
        new Create(redirectToRoute: 'sylius_backend_product_association_type_update'),
        new Update(redirectToRoute: 'sylius_backend_product_association_type_update'),
        new Delete(),
    ]))
;
