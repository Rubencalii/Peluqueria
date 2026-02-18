<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Entity\Consent;
use App\Service\ComplianceService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/compliance')]
class ComplianceController extends AbstractController
{
    #[Route('/incidents', name: 'api_compliance_incidents', methods: ['POST'])]
    #[IsGranted('ROLE_EMPLOYEE')]
    public function createIncident(Request $request, ComplianceService $complianceService, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $client = $em->getRepository(User::class)->find($data['clientId']);
        
        if (!$client) {
            return $this->json(['error' => 'Client not found'], 404);
        }

        $incident = $complianceService->recordIncident(
            $client,
            $data['type'],
            $data['description'],
            $data['severity'] ?? Incident::SEVERITY_MEDIUM
        );

        return $this->json([
            'id' => $incident->getId(),
            'message' => 'Incident recorded'
        ]);
    }

    #[Route('/medical-alerts/{id}', name: 'api_compliance_alerts', methods: ['GET'])]
    #[IsGranted('ROLE_EMPLOYEE')]
    public function getAlerts(User $client, ComplianceService $complianceService): JsonResponse
    {
        return $this->json($complianceService->checkMedicalAlerts($client));
    }

    #[Route('/consents', name: 'api_compliance_consents', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function signConsent(Request $request, EntityManagerInterface $em): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);

        $consent = new Consent();
        $consent->setUser($user);
        $consent->setType($data['type']);
        $consent->setAccepted($data['accepted']);
        $consent->setIpAddress($request->getClientIp());

        $em->persist($consent);
        $em->flush();

        return $this->json(['message' => 'Consent signed']);
    }
}
