<?php

namespace App\Controller\Api;

use App\Entity\Review;
use App\Entity\Appointment;
use App\Entity\ProfessionalNote;
use App\Entity\CustomerPreference;
use App\Entity\WaitingList;
use App\Repository\AppointmentRepository;
use App\Repository\ServiceRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/crm')]
class CRMController extends AbstractController
{
    #[Route('/reviews', name: 'api_review_create', methods: ['POST'])]
    public function createReview(Request $request, AppointmentRepository $appointmentRepo, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $appointment = $appointmentRepo->find($data['appointmentId'] ?? 0);

        if (!$appointment || $appointment->getStatus() !== Appointment::STATUS_COMPLETED) {
            return $this->json(['error' => 'Invalid appointment or not completed'], Response::HTTP_BAD_REQUEST);
        }

        if ($appointment->getReview()) {
            return $this->json(['error' => 'Review already exists for this appointment'], Response::HTTP_CONFLICT);
        }

        $review = new Review();
        $review->setRating($data['rating']);
        $review->setComment($data['comment'] ?? null);
        $review->setCustomer($appointment->getCustomer());
        $review->setAppointment($appointment);

        $em->persist($review);
        $em->flush();

        return $this->json(['message' => 'Review submitted successfully'], Response::HTTP_CREATED);
    }

    #[Route('/preferences', name: 'api_preferences_update', methods: ['POST'])]
    public function updatePreferences(Request $request, ServiceRepository $serviceRepo, EntityManagerInterface $em): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $data = json_decode($request->getContent(), true);
        $preferences = $user->getPreferences();

        if (!$preferences) {
            $preferences = new CustomerPreference();
            $preferences->setCustomer($user);
            $em->persist($preferences);
        }

        if (isset($data['visitFrequency'])) {
            $preferences->setVisitFrequency($data['visitFrequency']);
        }

        if (isset($data['habitualServiceIds'])) {
            // Clear existing and add new
            foreach ($preferences->getHabitualServices() as $service) {
                $preferences->removeHabitualService($service);
            }
            foreach ($data['habitualServiceIds'] as $id) {
                $service = $serviceRepo->find($id);
                if ($service) {
                    $preferences->addHabitualService($service);
                }
            }
        }

        $em->flush();

        return $this->json(['message' => 'Preferences updated successfully']);
    }

    #[Route('/notes', name: 'api_professional_note_create', methods: ['POST'])]
    #[IsGranted('ROLE_EMPLOYEE')]
    public function addProfessionalNote(Request $request, UserRepository $userRepo, EntityManagerInterface $em): JsonResponse
    {
        /** @var User $employee */
        $employee = $this->getUser();
        $data = json_decode($request->getContent(), true);
        $customer = $userRepo->find($data['customerId'] ?? 0);

        if (!$customer) {
            return $this->json(['error' => 'Customer not found'], Response::HTTP_NOT_FOUND);
        }

        $note = new ProfessionalNote();
        $note->setContent($data['content']);
        $note->setCustomer($customer);
        $note->setEmployee($employee);

        $em->persist($note);
        $em->flush();

        return $this->json(['message' => 'Note added successfully'], Response::HTTP_CREATED);
    }

    #[Route('/waiting-list', name: 'api_waiting_list_add', methods: ['POST'])]
    public function addToWaitingList(Request $request, ServiceRepository $serviceRepo, EntityManagerInterface $em): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $data = json_decode($request->getContent(), true);
        $service = $serviceRepo->find($data['serviceId'] ?? 0);

        $waitingItem = new WaitingList();
        $waitingItem->setCustomer($user);
        $waitingItem->setService($service);
        $waitingItem->setPreferredDate(new \DateTimeImmutable($data['date']));
        $waitingItem->setPreferredTimeRange($data['timeRange'] ?? null);

        $em->persist($waitingItem);
        $em->flush();

        return $this->json(['message' => 'Added to waiting list'], Response::HTTP_CREATED);
    }
}
