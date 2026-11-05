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

use App\Entity\ContactRequest;
use Sylius\Bundle\GridBundle\Builder\Action\DeleteAction;
use Sylius\Bundle\GridBundle\Builder\Action\ShowAction;
use Sylius\Bundle\GridBundle\Builder\Field\StringField;
use Sylius\Bundle\GridBundle\Builder\Field\TwigField;
use Sylius\Bundle\GridBundle\Builder\Filter\Filter;
use Sylius\Component\Grid\Attribute\AsGrid;
use Sylius\Component\Grid\Builder\GridBuilderInterface;

#[AsGrid(
    name: 'app_backend_contact_request',
    resourceClass: ContactRequest::class,
)]
final class ContactRequestGrid
{
    public function __invoke(GridBuilderInterface $gridBuilder): void
    {
        $gridBuilder
            ->orderBy('createdAt', 'desc')
            ->setLimits([10, 25, 50])
            ->withFields(
                TwigField::create('createdAt', 'backend/grid/field/date/medium.html.twig')
                    ->setLabel('sylius.ui.creation_date')
                    ->setSortable(true, 'createdAt'),
                TwigField::create('fullName', 'backend/grid/field/string.html.twig')
                    ->setPath('fullName')
                    ->setLabel('sylius.ui.name'),
                StringField::create('email')
                    ->setLabel('sylius.ui.email')
                    ->setSortable(true, 'email'),
            )
            ->withFilters(
                Filter::create('search', 'string')
                    ->setLabel('sylius.ui.search')
                    ->setOptions([
                        'fields' => [
                            'firstName',
                            'lastName',
                        ],
                    ]),
            )
            ->withItemActions(
                ShowAction::create(),
                DeleteAction::create(),
            )
        ;
    }
}
