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

namespace App\Twig\Context;

use App\Entity\Person;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\Twig\Context\Factory\ContextFactoryInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Adds the person of the URL slug to the template listing their games.
 */
final class PersonContextFactory implements ContextFactoryInterface
{
    /**
     * @param RepositoryInterface<Person> $personRepository
     */
    public function __construct(
        private readonly ContextFactoryInterface $decorated,
        #[Autowire(service: 'app.repository.person')]
        private readonly RepositoryInterface $personRepository,
    ) {
    }

    public function create(mixed $data, Operation $operation, Context $context): array
    {
        $slug = $context->get(RequestOption::class)?->request()?->attributes->get('slug');
        $person = $this->personRepository->findOneBy(['slug' => $slug]);

        if (!$person instanceof Person) {
            throw new NotFoundHttpException(sprintf('Person "%s" does not exist.', (string) $slug));
        }

        return array_merge($this->decorated->create($data, $operation, $context), ['person' => $person]);
    }
}
