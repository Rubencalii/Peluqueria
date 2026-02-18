<?php

namespace App\Controller\Api;

use App\Entity\Service;
use App\Service\AiAdvancedService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/ai-advanced')]
class AiAdvancedController extends AbstractController
{
    #[Route('/chat', name: 'api_ai_chat', methods: ['POST'])]
    public function chat(Request $request, AiAdvancedService $aiService): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $query = $data['query'] ?? '';
        
        return $this->json($aiService->processChatQuery($query));
    }

    #[Route('/dynamic-price/{id}', name: 'api_ai_dynamic_price', methods: ['GET'])]
    public function getPrice(Service $service, Request $request, AiAdvancedService $aiService): JsonResponse
    {
        $timeStr = $request->query->get('time', 'now');
        $time = new \DateTime($timeStr);
        
        return $this->json($aiService->calculateDynamicPrice($service, $time));
    }
}
