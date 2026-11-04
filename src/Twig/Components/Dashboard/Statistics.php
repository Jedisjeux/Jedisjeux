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

use App\Controller\StatisticsProvider;
use App\Repository\ArticleRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

/**
 * Website activity over a period, inspired by the Sylius e-commerce dashboard statistics.
 */
#[AsLiveComponent(name: 'Dashboard:Statistics', template: 'backend/dashboard/_statistics.html.twig')]
final class Statistics
{
    use DefaultActionTrait;

    public const PERIOD_TWO_WEEKS = '2 weeks';
    public const PERIOD_MONTH = 'month';
    public const PERIOD_YEAR = 'year';

    #[LiveProp]
    public string $startDate = 'first day of next month last year';

    #[LiveProp]
    public string $endDate = 'first day of next month this year';

    #[LiveProp]
    public string $period = self::PERIOD_YEAR;

    #[LiveProp]
    public string $interval = 'month';

    public function __construct(
        #[Autowire(service: 'app.repository.article')]
        private readonly ArticleRepository $articleRepository,
        private readonly StatisticsProvider $statisticsProvider,
    ) {
    }

    /**
     * @return array{articleCount: int, productCount: int, userCount: int, ratingCount: int}
     */
    #[ExposeInTemplate(name: 'summary')]
    public function getSummary(): array
    {
        return $this->statisticsProvider->getStatisticsForPeriod(
            new \DateTimeImmutable($this->startDate),
            new \DateTimeImmutable($this->endDate),
        );
    }

    /**
     * @return array<string, int>
     */
    #[ExposeInTemplate(name: 'totals')]
    public function getTotals(): array
    {
        return $this->statisticsProvider->getStatistics();
    }

    #[ExposeInTemplate(name: 'period_label')]
    public function getPeriodLabel(): string
    {
        // The end date is excluded from the period
        return sprintf(
            '%s – %s',
            (new \DateTimeImmutable($this->startDate))->format('d/m/Y'),
            (new \DateTimeImmutable($this->endDate))->modify('-1 day')->format('d/m/Y'),
        );
    }

    /**
     * @return array{intervals: string[], articles_count: int[], ranges: array<array{from: string, to: string}>}
     */
    #[ExposeInTemplate(name: 'statistics')]
    public function getStatistics(): array
    {
        $startDate = new \DateTimeImmutable($this->startDate);
        $endDate = new \DateTimeImmutable($this->endDate);
        $format = 'month' === $this->interval ? 'Y-m' : 'Y-m-d';

        $counts = [];
        foreach (new \DatePeriod($startDate, $this->resolveInterval(), $endDate) as $date) {
            $counts[$date->format($format)] = 0;
        }

        foreach ($this->articleRepository->findPublishStartDatesBetween($startDate, $endDate) as $publishStartDate) {
            $key = $publishStartDate->format($format);

            if (array_key_exists($key, $counts)) {
                ++$counts[$key];
            }
        }

        return [
            'intervals' => array_map(
                fn (string $key): string => $this->formatLabel($key),
                array_keys($counts),
            ),
            'articles_count' => array_values($counts),
            // Dates of each bar, used to open the article list filtered on them when clicking on it
            'ranges' => array_map(
                fn (string $key): array => $this->resolveRange($key),
                array_keys($counts),
            ),
        ];
    }

    #[LiveAction]
    public function changeRange(#[LiveArg] string $period, #[LiveArg] string $interval): void
    {
        $this->period = $period;
        $this->interval = $interval;

        [$this->startDate, $this->endDate] = match ($this->period) {
            self::PERIOD_YEAR => [
                $this->formatDate('first day of next month last year'),
                $this->formatDate('first day of next month this year'),
            ],
            self::PERIOD_MONTH => [
                $this->formatDate('+1 day -1 month'),
                $this->formatDate('+1 day'),
            ],
            self::PERIOD_TWO_WEEKS => [
                $this->formatDate('+1 day -2 weeks'),
                $this->formatDate('+1 day'),
            ],
            default => throw new \InvalidArgumentException(sprintf('Period "%s" is not supported.', $this->period)),
        };
    }

    #[LiveAction]
    public function getPreviousPeriod(): void
    {
        $this->shiftPeriod('sub');
    }

    #[LiveAction]
    public function getNextPeriod(): void
    {
        $this->shiftPeriod('add');
    }

    private function shiftPeriod(string $method): void
    {
        $interval = new \DateInterval(match ($this->period) {
            self::PERIOD_YEAR => 'P1Y',
            self::PERIOD_MONTH => 'P1M',
            self::PERIOD_TWO_WEEKS => 'P2W',
            default => throw new \InvalidArgumentException(sprintf('Period "%s" is not supported.', $this->period)),
        });

        $this->startDate = (new \DateTimeImmutable($this->startDate))->{$method}($interval)->format('Y-m-d');
        $this->endDate = (new \DateTimeImmutable($this->endDate))->{$method}($interval)->format('Y-m-d');
    }

    private function resolveInterval(): \DateInterval
    {
        return new \DateInterval(match ($this->interval) {
            'day' => 'P1D',
            'month' => 'P1M',
            default => throw new \InvalidArgumentException(sprintf('Interval "%s" is not supported.', $this->interval)),
        });
    }

    /**
     * @return array{from: string, to: string}
     */
    private function resolveRange(string $key): array
    {
        if ('month' === $this->interval) {
            $date = \DateTimeImmutable::createFromFormat('!Y-m', $key);

            return ['from' => $date->format('Y-m-01'), 'to' => $date->format('Y-m-t')];
        }

        return ['from' => $key, 'to' => $key];
    }

    private function formatLabel(string $key): string
    {
        if ('month' === $this->interval) {
            return \DateTimeImmutable::createFromFormat('!Y-m', $key)->format('m/Y');
        }

        return \DateTimeImmutable::createFromFormat('!Y-m-d', $key)->format('d/m');
    }

    private function formatDate(string $datetime): string
    {
        return (new \DateTimeImmutable($datetime))->format('Y-m-d');
    }
}
