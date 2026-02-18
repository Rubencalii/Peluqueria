<?php

namespace App\Controller\Api;

use App\Service\LoyaltyManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/loyalty')]
class LoyaltyController extends AbstractController
{
    #[Route('/status', name: 'api_loyalty_status', methods: ['GET'])]
    public function getStatus(LoyaltyManager $loyaltyManager): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $points = $user->getLoyaltyPoints()?->getPoints() ?? 0;
        $level = $loyaltyManager->getCustomerLevel($user);

        return $this->json([
            'points' => $points,
            'level' => $level,
            'lastUpdated' => $user->getLoyaltyPoints()?->getLastUpdated()?->format('c'),
        ]);
    }
}
