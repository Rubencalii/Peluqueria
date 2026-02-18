<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Entity\VacationRequest;
use App\Repository\VacationRequestRepository;
use App\Service\StaffService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/staff')]
class StaffController extends AbstractController
{
    #[Route('/commissions', name: 'api_staff_commissions', methods: ['GET'])]
    #[IsGranted('ROLE_EMPLOYEE')]
    public function getMyCommissions(Request $request, StaffService $staffService): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $start = new \DateTime($request->query->get('start', 'first day of this month'));
        $end = new \DateTime($request->query->get('end', 'now'));

        return $this->json($staffService->calculateCommissions($user, $start, $end));
    }

    #[Route('/admin/commissions/{id}', name: 'api_staff_admin_commissions', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function getEmployeeCommissions(User $employee, Request $request, StaffService $staffService): JsonResponse
    {
        $start = new \DateTime($request->query->get('start', 'first day of this month'));
        $end = new \DateTime($request->query->get('end', 'now'));

        return $this->json($staffService->calculateCommissions($employee, $start, $end));
    }

    #[Route('/vacations', name: 'api_staff_vacations_list', methods: ['GET'])]
    #[IsGranted('ROLE_EMPLOYEE')]
    public function listMyVacations(): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $vacations = $user->getVacationRequests();

        $data = array_map(fn($v) => [
            'id' => $v->getId(),
            'start' => $v->getStartDate()->format('Y-m-d'),
            'end' => $v->getEndDate()->format('Y-m-d'),
            'status' => $v->getStatus(),
            'reason' => $v->getReason(),
        ], $vacations->toArray());

        return $this->json($data);
    }

    #[Route('/vacations/request', name: 'api_staff_vacations_request', methods: ['POST'])]
    #[IsGranted('ROLE_EMPLOYEE')]
    public function requestVacation(Request $request, EntityManagerInterface $em): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);

        $vacation = new VacationRequest();
        $vacation->setEmployee($user);
        $vacation->setStartDate(new \DateTime($data['startDate']));
        $vacation->setEndDate(new \DateTime($data['endDate']));
        $vacation->setReason($data['reason'] ?? null);

        $em->persist($vacation);
        $em->flush();

        return $this->json(['message' => 'Vacation requested'], Response::HTTP_CREATED);
    }

    #[Route('/admin/vacations/{id}/status', name: 'api_staff_admin_vacations_status', methods: ['PATCH'])]
    #[IsGranted('ROLE_ADMIN')]
    public function updateVacationStatus(VacationRequest $vacation, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        if (isset($data['status'])) {
            $vacation->setStatus($data['status']);
        }
        if (isset($data['adminNote'])) {
            $vacation->setAdminNote($data['adminNote']);
        }

        $em->flush();

        return $this->json(['message' => 'Vacation status updated']);
    }
}
