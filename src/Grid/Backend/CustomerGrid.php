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

namespace App\Grid\Backend;

use App\Entity\Customer;
use Sylius\Bundle\GridBundle\Builder\Action\ShowAction;
use Sylius\Bundle\GridBundle\Builder\Action\UpdateAction;
use Sylius\Bundle\GridBundle\Builder\Field\StringField;
use Sylius\Bundle\GridBundle\Builder\Field\TwigField;
use Sylius\Bundle\GridBundle\Builder\Filter\Filter;
use Sylius\Component\Grid\Attribute\AsGrid;
use Sylius\Component\Grid\Builder\GridBuilderInterface;

#[AsGrid(
    name: 'sylius_backend_customer',
    resourceClass: Customer::class,
)]
final class CustomerGrid
{
    public function __invoke(GridBuilderInterface $gridBuilder): void
    {
        $gridBuilder
            ->setRepositoryMethod('createListQueryBuilder')
            ->orderBy('createdAt', 'desc')
            ->setLimits([10, 25, 50])
            ->withFields(
                TwigField::create('avatar', 'backend/customer/grid/field/avatar.html.twig')
                    ->setPath('.')
                    ->setLabel('app.ui.avatar'),
                StringField::create('username')
                    ->setPath('user.username')
                    ->setLabel('sylius.ui.username')
                    ->setSortable(true, 'user.username'),
                StringField::create('email')
                    ->setLabel('sylius.ui.email')
                    ->setSortable(true, 'email'),
                TwigField::create('createdAt', 'backend/grid/field/date/medium.html.twig')
                    ->setLabel('sylius.ui.registration_date')
                    ->setSortable(true, 'createdAt'),
                TwigField::create('enabled', 'backend/customer/grid/field/enabled.html.twig')
                    ->setPath('.')
                    ->setLabel('sylius.ui.enabled'),
                TwigField::create('verified', 'backend/customer/grid/field/verified.html.twig')
                    ->setPath('.')
                    ->setLabel('sylius.ui.verified'),
            )
            ->withFilters(
                Filter::create('search', 'string')
                    ->setLabel('sylius.ui.search')
                    ->setOptions([
                        'fields' => [
                            'email',
                            'user.username',
                        ],
                    ]),
            )
            ->withItemActions(
                ShowAction::create(),
                UpdateAction::create(),
            )
        ;
    }
}
