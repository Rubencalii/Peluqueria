<?php

namespace App\Controller\Admin;

use App\Entity\Appointment;
use App\Entity\Service;
use App\Entity\User;
use App\Form\ServiceType;
use App\Repository\AppointmentRepository;
use App\Repository\ServiceRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('', name: 'app_admin_dashboard')]
    public function dashboard(
        AppointmentRepository $appointmentRepo,
        ServiceRepository $serviceRepo,
        UserRepository $userRepo,
    ): Response {
        $today = new \DateTime('today');
        $tomorrow = new \DateTime('tomorrow');

        $appointments = $appointmentRepo->createQueryBuilder('a')
            ->where('a.startAt >= :today')
            ->andWhere('a.startAt < :tomorrow')
            ->setParameter('today', $today)
            ->setParameter('tomorrow', $tomorrow)
            ->orderBy('a.startAt', 'ASC')
            ->getQuery()
            ->getResult();

        $todayRevenue = 0;
        foreach ($appointments as $appointment) {
            $todayRevenue += (float) $appointment->getService()->getPrice();
        }

        return $this->render('admin/dashboard.html.twig', [
            'todayAppointmentsCount' => count($appointments),
            'todayRevenue' => $todayRevenue,
            'newCustomersCount' => 3, // Placeholder
            'activeServicesCount' => count($serviceRepo->findBy(['active' => true])),
            'appointments' => $appointments,
            'employees' => $userRepo->findEmployees(),
        ]);
    }

    #[Route('/customers/{id}', name: 'app_admin_customer_detail')]
    public function customerDetail(User $customer, AppointmentRepository $appointmentRepo): Response
    {
        return $this->render('admin/customers/detail.html.twig', [
            'customer' => $customer,
            'appointments' => $appointmentRepo->findBy(['customer' => $customer], ['startAt' => 'DESC']),
        ]);
    }

    // --- SERVICES CRUD ---

    #[Route('/services', name: 'app_admin_services')]
    public function services(ServiceRepository $serviceRepo): Response
    {
        return $this->render('admin/services/index.html.twig', [
            'services' => $serviceRepo->findAll(),
        ]);
    }

    #[Route('/services/new', name: 'app_admin_service_new')]
    public function serviceNew(Request $request, EntityManagerInterface $em): Response
    {
        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($service);
            $em->flush();
            $this->addFlash('success', 'Servicio creado correctamente.');
            return $this->redirectToRoute('app_admin_services');
        }

        return $this->render('admin/services/form.html.twig', [
            'form' => $form,
            'service' => $service,
            'isNew' => true,
        ]);
    }

    #[Route('/services/{id}/edit', name: 'app_admin_service_edit')]
    public function serviceEdit(Service $service, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Servicio actualizado correctamente.');
            return $this->redirectToRoute('app_admin_services');
        }

        return $this->render('admin/services/form.html.twig', [
            'form' => $form,
            'service' => $service,
            'isNew' => false,
        ]);
    }

    #[Route('/services/{id}/delete', name: 'app_admin_service_delete', methods: ['POST'])]
    public function serviceDelete(Service $service, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $service->getId(), $request->request->get('_token'))) {
            $service->setActive(false);
            $em->flush();
            $this->addFlash('success', 'Servicio desactivado correctamente.');
        }
        return $this->redirectToRoute('app_admin_services');
    }

    // --- APPOINTMENTS ---

    #[Route('/appointments', name: 'app_admin_appointments')]
    public function appointments(AppointmentRepository $appointmentRepo, Request $request): Response
    {
        $date = $request->query->get('date', (new \DateTime())->format('Y-m-d'));
        $dateObj = new \DateTime($date);
        $start = (clone $dateObj)->setTime(0, 0);
        $end = (clone $dateObj)->setTime(23, 59, 59);

        $appointments = $appointmentRepo->createQueryBuilder('a')
            ->andWhere('a.startAt BETWEEN :start AND :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->orderBy('a.startAt', 'ASC')
            ->getQuery()
            ->getResult();

        return $this->render('admin/appointments/index.html.twig', [
            'appointments' => $appointments,
            'currentDate' => $dateObj,
        ]);
    }

    #[Route('/appointments/{id}/status/{status}', name: 'app_admin_appointment_status', methods: ['POST'])]
    public function appointmentStatus(Appointment $appointment, string $status, EntityManagerInterface $em): Response
    {
        $validStatuses = [
            Appointment::STATUS_CONFIRMED,
            Appointment::STATUS_COMPLETED,
            Appointment::STATUS_CANCELLED,
            Appointment::STATUS_NO_SHOW,
        ];

        if (in_array($status, $validStatuses)) {
            $appointment->setStatus($status);
            $em->flush();
            $this->addFlash('success', 'Estado de la cita actualizado.');
        }

        return $this->redirectToRoute('app_admin_appointments');
    }

    // --- EMPLOYEES ---

    #[Route('/employees', name: 'app_admin_employees')]
    public function employees(UserRepository $userRepo): Response
    {
        return $this->render('admin/employees/index.html.twig', [
            'employees' => $userRepo->findEmployees(),
        ]);
    }
}
