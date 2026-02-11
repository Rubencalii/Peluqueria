<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Entity\Service;
use App\Entity\User;
use App\Repository\AppointmentRepository;
use App\Repository\ServiceRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class ApiController extends AbstractController
{
    #[Route('/services', name: 'api_services', methods: ['GET'])]
    public function getServices(ServiceRepository $serviceRepo): JsonResponse
    {
        $services = $serviceRepo->findBy(['active' => true]);
        $data = array_map(fn($s) => [
            'id' => $s->getId(),
            'name' => $s->getName(),
            'description' => $s->getDescription(),
            'price' => $s->getPrice(),
            'duration' => $s->getDuration(),
        ], $services);

        return $this->json($data);
    }

    #[Route('/employees', name: 'api_employees', methods: ['GET'])]
    public function getEmployees(UserRepository $userRepo, Request $request): JsonResponse
    {
        $serviceId = $request->query->get('service');
        $employees = $userRepo->findEmployees(); // MVP: All employees for now
        
        $data = array_map(fn($e) => [
            'id' => $e->getId(),
            'name' => $e->getName(),
            'email' => $e->getEmail(),
        ], $employees);

        return $this->json($data);
    }

    #[Route('/availability', name: 'api_availability', methods: ['GET'])]
    public function getAvailability(Request $request): JsonResponse
    {
        // To be implemented with AvailabilityService
        return $this->json(['slots' => ['09:00', '10:00', '11:00']]);
    }
}
