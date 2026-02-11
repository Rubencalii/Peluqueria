<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Entity\Service;
use App\Entity\User;
use App\Repository\AppointmentRepository;
use App\Repository\ServiceRepository;
use App\Service\BookingNotificationService;
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
        return $this->json(['slots' => ['09:00', '10:00', '11:00', '12:00', '16:00', '17:00']]);
    }

    #[Route('/create-payment-intent', name: 'api_create_payment_intent', methods: ['POST'])]
    public function createPaymentIntent(Request $request, ServiceRepository $serviceRepo): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $serviceId = $data['serviceId'] ?? null;

        if (!$serviceId) {
            return $this->json(['error' => 'Service not specified'], Response::HTTP_BAD_REQUEST);
        }

        $service = $serviceRepo->find($serviceId);
        if (!$service) {
            return $this->json(['error' => 'Service not found'], Response::HTTP_NOT_FOUND);
        }

        // Calculate amount (Stripe uses cents)
        // We charge a deposit (e.g., 20% or a fixed 5.00€)
        $amount = 500; // 5.00€ for now

        \Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

        try {
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $amount,
                'currency' => 'eur',
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
                'metadata' => [
                    'service_id' => $service->getId(),
                ]
            ]);

            return $this->json([
                'clientSecret' => $paymentIntent->client_secret,
            ]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/appointments', name: 'api_appointment_create', methods: ['POST'])]
    public function createAppointment(
        Request $request,
        UserRepository $userRepo,
        ServiceRepository $serviceRepo,
        EntityManagerInterface $em,
        BookingNotificationService $notificationService
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $customer = $userRepo->find($data['customerId'] ?? 1); // Mock customer if not logged in
        $employee = $userRepo->find($data['employeeId']);
        $service = $serviceRepo->find($data['serviceId']);
        
        if (!$employee || !$service) {
            return $this->json(['error' => 'Employee or service not found'], Response::HTTP_BAD_REQUEST);
        }

        $appointment = new Appointment();
        $appointment->setCustomer($customer);
        $appointment->setEmployee($employee);
        $appointment->setService($service);
        $appointment->setStartAt(new \DateTime($data['date'] . ' ' . $data['time']));
        $appointment->setStatus(Appointment::STATUS_CONFIRMED);
        $appointment->setPaymentMethod(Appointment::PAYMENT_ONLINE);

        $em->persist($appointment);
        $em->flush();

        // Send Notification (Async via Messenger if configured)
        try {
            $notificationService->sendConfirmation($appointment);
        } catch (\Exception $e) {
            // Log error but don't fail appointment creation
        }

        return $this->json([
            'id' => $appointment->getId(),
            'message' => 'Cita reservada con éxito',
        ], Response::HTTP_CREATED);
    }
}
