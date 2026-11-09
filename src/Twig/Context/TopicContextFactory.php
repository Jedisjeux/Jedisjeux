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

use App\Entity\Taxon;
use App\Entity\Topic;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\Twig\Context\Factory\ContextFactoryInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Adds the topic of the URL to the template listing its posts, which only the staff can see in a private category.
 */
final class TopicContextFactory implements ContextFactoryInterface
{
    /**
     * @param RepositoryInterface<Topic> $topicRepository
     */
    public function __construct(
        private readonly ContextFactoryInterface $decorated,
        #[Autowire(service: 'app.repository.topic')]
        private readonly RepositoryInterface $topicRepository,
        private readonly Security $security,
    ) {
    }

    public function create(mixed $data, Operation $operation, Context $context): array
    {
        $topicId = $context->get(RequestOption::class)?->request()?->attributes->get('topicId');
        $topic = $this->topicRepository->find($topicId);

        if (!$topic instanceof Topic) {
            throw new NotFoundHttpException(sprintf('Topic "%s" does not exist.', (string) $topicId));
        }

        $mainTaxon = $topic->getMainTaxon();

        if ($mainTaxon instanceof Taxon && !$mainTaxon->isPublic() && !$this->security->isGranted('ROLE_STAFF')) {
            throw new AccessDeniedException();
        }

        return array_merge($this->decorated->create($data, $operation, $context), ['topic' => $topic]);
    }
}
