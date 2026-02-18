<?php

namespace App\Controller;

use App\Service\AnalyticsService;
use App\Service\FinanceService;
use App\Service\InventoryService;
use App\Service\StaffService;
use App\Repository\SalonRepository;
use App\Repository\UserRepository;
use App\Repository\AppointmentRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    public function __construct(
        private AnalyticsService $analyticsService,
        private FinanceService $financeService,
        private InventoryService $inventoryService,
        private StaffService $staffService,
        private SalonRepository $salonRepo,
        private UserRepository $userRepo,
        private AppointmentRepository $appointmentRepo,
        private ProductRepository $productRepo
    ) {}

    #[Route('/', name: 'app_admin_dashboard')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'stats' => $this->analyticsService->getKPIDashboard(),
            'salons' => $this->salonRepo->findAll(),
            'today_appointments' => $this->appointmentRepo->findBy(['startAt' => new \DateTime('today')]),
        ]);
    }

    #[Route('/agenda', name: 'app_admin_agenda')]
    public function agenda(): Response
    {
        return $this->render('admin/agenda.html.twig', [
            'staff' => $this->userRepo->findByRole('ROLE_EMPLOYEE'),
            'appointments' => $this->appointmentRepo->findAll(),
        ]);
    }

    #[Route('/clientes', name: 'app_admin_clients')]
    public function clients(): Response
    {
        return $this->render('admin/clients.html.twig', [
            'clients' => $this->userRepo->findByRole('ROLE_CUSTOMER'),
        ]);
    }

    #[Route('/inventario', name: 'app_admin_inventory')]
    public function inventory(): Response
    {
        return $this->render('admin/inventory.html.twig', [
            'products' => $this->productRepo->findAll(),
            'low_stock' => $this->productRepo->findLowStock(),
        ]);
    }

    #[Route('/personal', name: 'app_admin_staff')]
    public function staff(): Response
    {
        return $this->render('admin/staff.html.twig', [
            'staff' => $this->userRepo->findByRole('ROLE_EMPLOYEE'),
        ]);
    }

    #[Route('/finanzas', name: 'app_admin_finance')]
    public function finance(): Response
    {
        return $this->render('admin/finance.html.twig', [
            'reports' => $this->financeService->generateFullReport(new \DateTime('first day of this month'), new \DateTime('last day of this month')),
        ]);
    }
}
