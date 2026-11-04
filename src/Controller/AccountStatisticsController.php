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

namespace App\Controller;

use App\Repository\GamePlayRepository;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Game plays statistics of the current customer, year by year.
 */
final class AccountStatisticsController extends AbstractController
{
    public function __construct(
        private readonly CustomerContextInterface $customerContext,
        #[Autowire(service: 'app.repository.game_play')]
        private readonly GamePlayRepository $gamePlayRepository,
    ) {
    }

    public function indexAction(Request $request): Response
    {
        $customer = $this->customerContext->getCustomer();

        if (null === $customer) {
            throw new AccessDeniedHttpException();
        }

        $authorId = (int) $customer->getId();
        $years = $this->gamePlayRepository->findPlayedYearsByAuthor($authorId);
        $year = $request->query->getInt('annee', $years[0] ?? (int) date('Y'));

        $startDate = new \DateTimeImmutable(sprintf('%d-01-01', $year));
        $endDate = $startDate->modify('+1 year');
        $months = $this->gamePlayRepository->countByMonthForAuthorBetween($authorId, $startDate, $endDate);

        return $this->render('frontend/account/statistics/index.html.twig', [
            'year' => $year,
            'years' => $years,
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
                $request->getLocale(),
            ),
        ]);
    }
}
