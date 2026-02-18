<?php

namespace App\Controller\Api;

use App\Entity\Appointment;
use App\Entity\User;
use App\Repository\NotificationRepository;
use App\Service\EcoService;
use App\Service\PushService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/premium')]
class PremiumController extends AbstractController
{
    #[Route('/notifications', name: 'api_premium_notifications', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function getMyNotifications(NotificationRepository $notifRepo): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $notifs = $notifRepo->findBy(['user' => $user], ['createdAt' => 'DESC']);
        
        return $this->json(array_map(fn($n) => [
            'id' => $n->getId(),
            'title' => $n->getTitle(),
            'message' => $n->getMessage(),
            'read' => $n->isRead(),
            'date' => $n->getCreatedAt()->format('Y-m-d H:i'),
        ], $notifs));
    }

    #[Route('/eco-impact/{id}', name: 'api_premium_eco', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function triggerEcoImpact(Appointment $appointment, EcoService $ecoService): JsonResponse
    {
        $record = $ecoService->generateEcoImpact($appointment);
        return $this->json([
            'carbonSaved' => $record->getCarbonSaved(),
            'waterSaved' => $record->getWaterSaved(),
            'donation' => $record->getDonationAmount(),
            'message' => 'Eco impact recorded successfully.'
        ]);
    }

    #[Route('/test-push', name: 'api_premium_push_test', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function testPush(PushService $pushService): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $pushService->sendNotification($user, '¡Hola Luxe!', 'Esta es una notificación de prueba de tu salón premium.');
        return $this->json(['message' => 'Notification triggered']);
    }
}
