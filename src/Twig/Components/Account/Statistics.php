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

namespace App\Twig\Components\Account;

use App\Repository\GamePlayRepository;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Metadata\UrlMapping;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;
use Symfony\UX\TwigComponent\Attribute\PostMount;

/**
 * Game plays statistics of the current customer, year by year.
 */
#[AsLiveComponent(name: 'Account:Statistics', template: 'frontend/account/statistics/_statistics.html.twig')]
final class Statistics
{
    use DefaultActionTrait;

    // Games shown on the podium, the others are listed below with a pagination
    public const PODIUM_SIZE = 3;

    public const RANKING_PAGE_SIZE = 10;

    #[LiveProp(url: new UrlMapping(as: 'annee'))]
    public ?int $year = null;

    #[LiveProp(url: new UrlMapping(as: 'page'))]
    public int $page = 1;

    /** @var list<int>|null */
    private ?array $years = null;

    /** @var array<string, mixed>|null */
    private ?array $statistics = null;

    public function __construct(
        private readonly CustomerContextInterface $customerContext,
        #[Autowire(service: 'app.repository.game_play')]
        private readonly GamePlayRepository $gamePlayRepository,
        private readonly RequestStack $requestStack,
    ) {
    }

    #[PostMount]
    public function resolveYear(): void
    {
        $this->year ??= $this->getYears()[0] ?? (int) date('Y');
    }

    #[LiveAction]
    public function changeYear(#[LiveArg] int $year): void
    {
        $this->year = $year;
        $this->page = 1;
    }

    #[LiveAction]
    public function changePage(#[LiveArg] int $page): void
    {
        $this->page = $page;
    }

    /**
     * @return list<int>
     */
    #[ExposeInTemplate]
    public function getYears(): array
    {
        return $this->years ??= $this->gamePlayRepository->findPlayedYearsByAuthor($this->getAuthorId());
    }

    /**
     * @return array{
     *     totals: array{gamePlayCount: int, productCount: int},
     *     previousTotals: array{gamePlayCount: int, productCount: int}|null,
     *     months: array<int, int>,
     *     busiestMonth: int|null,
     *     mostPlayed: list<array{name: string, slug: string, imagePath: string|null, gamePlayCount: int}>,
     * }
     */
    #[ExposeInTemplate('statistics')]
    public function getStatistics(): array
    {
        if (null !== $this->statistics) {
            return $this->statistics;
        }

        $authorId = $this->getAuthorId();
        $years = $this->getYears();
        $year = $this->year ?? $years[0] ?? (int) date('Y');

        $startDate = new \DateTimeImmutable(sprintf('%d-01-01', $year));
        $endDate = $startDate->modify('+1 year');
        $months = $this->gamePlayRepository->countByMonthForAuthorBetween($authorId, $startDate, $endDate);

        return $this->statistics = [
            'totals' => $this->gamePlayRepository->countByAuthorBetween($authorId, $startDate, $endDate),
            'previousTotals' => in_array($year - 1, $years, true)
                ? $this->gamePlayRepository->countByAuthorBetween($authorId, $startDate->modify('-1 year'), $startDate)
                : null,
            'months' => $months,
            'busiestMonth' => max($months) > 0 ? (int) array_search(max($months), $months, true) : null,
            'mostPlayed' => $this->gamePlayRepository->findMostPlayedByAuthorBetween(
                $authorId,
                $startDate,
                $endDate,
                $this->requestStack->getCurrentRequest()?->getLocale() ?? 'fr',
            ),
        ];
    }

    /**
     * Games ranked after the podium.
     *
     * @return Pagerfanta<array{name: string, slug: string, imagePath: string|null, gamePlayCount: int}>
     */
    #[ExposeInTemplate]
    public function getRanking(): Pagerfanta
    {
        $ranking = new Pagerfanta(new ArrayAdapter(array_slice($this->getStatistics()['mostPlayed'], self::PODIUM_SIZE)));
        $ranking->setMaxPerPage(self::RANKING_PAGE_SIZE);
        $ranking->setCurrentPage(min(max(1, $this->page), $ranking->getNbPages()));

        return $ranking;
    }

    private function getAuthorId(): int
    {
        $customer = $this->customerContext->getCustomer();

        if (null === $customer) {
            throw new AccessDeniedHttpException();
        }

        return (int) $customer->getId();
    }
}
