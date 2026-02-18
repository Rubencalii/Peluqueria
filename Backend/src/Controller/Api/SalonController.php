<?php

namespace App\Controller\Api;

use App\Entity\Salon;
use App\Repository\SalonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/salons')]
class SalonController extends AbstractController
{
    #[Route('', name: 'api_salons_list', methods: ['GET'])]
    public function list(SalonRepository $salonRepo): JsonResponse
    {
        $salons = $salonRepo->findAll();
        $data = array_map(fn($s) => [
            'id' => $s->getId(),
            'name' => $s->getName(),
            'address' => $s->getAddress(),
            'phone' => $s->getPhone(),
            'email' => $s->getEmail(),
        ], $salons);

        return $this->json($data);
    }

    #[Route('', name: 'api_salons_create', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        $salon = new Salon();
        $salon->setName($data['name']);
        $salon->setAddress($data['address']);
        $salon->setPhone($data['phone'] ?? null);
        $salon->setEmail($data['email'] ?? null);

        $em->persist($salon);
        $em->flush();

        return $this->json([
            'id' => $salon->getId(),
            'message' => 'Salon created successfully'
        ], Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'api_salons_show', methods: ['GET'])]
    public function show(Salon $salon): JsonResponse
    {
        return $this->json([
            'id' => $salon->getId(),
            'name' => $salon->getName(),
            'address' => $salon->getAddress(),
            'staff_count' => count($salon->getStaff()),
        ]);
    }
}
