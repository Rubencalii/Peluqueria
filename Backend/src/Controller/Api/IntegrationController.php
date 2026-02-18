<?php

namespace App\Controller\Api;

use App\Entity\Appointment;
use App\Entity\BeforeAfterGallery;
use App\Service\IntegrationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/integrations')]
class IntegrationController extends AbstractController
{
    #[Route('/calendar/{id}', name: 'api_integration_calendar', methods: ['GET'])]
    public function getCalendarLink(Appointment $appointment, IntegrationService $integrationService): JsonResponse
    {
        return $this->json([
            'google_calendar_url' => $integrationService->generateGoogleCalendarUrl($appointment)
        ]);
    }

    #[Route('/social-share/{id}', name: 'api_integration_social', methods: ['GET'])]
    public function getSocialTemplates(BeforeAfterGallery $photo, IntegrationService $integrationService): JsonResponse
    {
        return $this->json($integrationService->generateSocialMediaTemplate($photo));
    }
}
