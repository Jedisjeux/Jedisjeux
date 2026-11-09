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

namespace App\Twig\Components\Dashboard;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsTwigComponent(name: 'Dashboard:LatestCustomers', template: 'backend/dashboard/_customers.html.twig')]
final class LatestCustomers
{
    public int $count = 5;

    public function __construct(
        #[Autowire(service: 'sylius.repository.customer')]
        private readonly CustomerRepository $customerRepository,
    ) {
    }

    /**
     * @return Customer[]
     */
    #[ExposeInTemplate]
    public function getCustomers(): array
    {
        return $this->customerRepository->findLatest($this->count);
    }
}
