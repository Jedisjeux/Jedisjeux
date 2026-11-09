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

namespace App\Twig\Components\Topic;

use App\Entity\Topic;
use App\Repository\TopicRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsTwigComponent(name: 'Topic:Latest', template: 'frontend/topic/_simpleList.html.twig')]
final class Latest
{
    public int $count = 4;

    public function __construct(
        #[Autowire(service: 'app.repository.topic')]
        private readonly TopicRepository $topicRepository,
        private readonly Security $security,
    ) {
    }

    /**
     * @return iterable<Topic>
     */
    #[ExposeInTemplate]
    public function getTopics(): iterable
    {
        $topics = $this->topicRepository->createFilterPaginator(
            [],
            ['createdAt' => 'desc'],
            $this->security->isGranted('ROLE_STAFF'),
        );
        $topics->setMaxPerPage($this->count);

        return $topics->getCurrentPageResults();
    }
}
