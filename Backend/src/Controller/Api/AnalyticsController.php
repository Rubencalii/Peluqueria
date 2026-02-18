<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Service\AiOptimizationService;
use App\Service\AnalyticsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/analytics')]
class AnalyticsController extends AbstractController
{
    #[Route('/dashboard', name: 'api_analytics_dashboard', methods: ['GET'])]
    #[IsGranted('ROLE_EMPLOYEE')]
    public function getDashboardStats(Request $request, AnalyticsService $analyticsService): JsonResponse
    {
        $start = new \DateTime($request->query->get('start', 'first day of this month'));
        $end = new \DateTime($request->query->get('end', 'now'));

        $stats = $analyticsService->calculateKPIs($start, $end);
        $retention = $analyticsService->getRetentionRate();

        return $this->json([
            'period' => [
                'start' => $start->format('Y-m-d'),
                'end' => $end->format('Y-m-d'),
            ],
            'kpis' => $stats,
            'retentionRate' => $retention,
        ]);
    }

    #[Route('/forecast', name: 'api_analytics_forecast', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function getForecast(Request $request, AnalyticsService $analyticsService): JsonResponse
    {
        $date = new \DateTime($request->query->get('date', 'tomorrow'));
        $forecast = $analyticsService->predictDemand($date);

        return $this->json($forecast);
    }

    #[Route('/suggestions', name: 'api_analytics_suggestions', methods: ['GET'])]
    public function getSuggestions(AiOptimizationService $aiService): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $suggestions = $aiService->getUpsellingSuggestions($user);

        return $this->json([
            'customer' => $user->getName(),
            'suggestions' => $suggestions,
        ]);
    }

    #[Route('/optimization', name: 'api_analytics_optimization', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function getOptimization(AiOptimizationService $aiService): JsonResponse
    {
        return $this->json($aiService->optimizeSchedules());
    }
}
