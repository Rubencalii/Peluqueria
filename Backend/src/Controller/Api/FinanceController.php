<?php

namespace App\Controller\Api;

use App\Entity\Appointment;
use App\Entity\Supplier;
use App\Repository\AppointmentRepository;
use App\Repository\SupplierRepository;
use App\Service\FinanceService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/finance')]
#[IsGranted('ROLE_ADMIN')]
class FinanceController extends AbstractController
{
    #[Route('/invoices', name: 'api_invoice_list', methods: ['GET'])]
    public function listInvoices(FinanceService $financeService): JsonResponse
    {
        return $this->json($financeService->getFinancialStats());
    }

    #[Route('/invoices/generate/{id}', name: 'api_invoice_generate', methods: ['POST'])]
    public function generate(Appointment $appointment, FinanceService $financeService): JsonResponse
    {
        if ($appointment->getStatus() !== Appointment::STATUS_COMPLETED) {
            return $this->json(['error' => 'Appointment must be completed to generate an invoice'], Response::HTTP_BAD_REQUEST);
        }

        $invoice = $financeService->generateInvoiceForAppointment($appointment);

        return $this->json([
            'number' => $invoice->getNumber(),
            'total' => $invoice->getTotalAmount(),
            'createdAt' => $invoice->getCreatedAt()->format('c'),
        ]);
    }

    #[Route('/suppliers', name: 'api_supplier_list', methods: ['GET'])]
    public function listSuppliers(SupplierRepository $supplierRepo): JsonResponse
    {
        $suppliers = $supplierRepo->findAll();
        $data = array_map(fn($s) => [
            'id' => $s->getId(),
            'name' => $s->getName(),
            'email' => $s->getContactEmail(),
            'phone' => $s->getPhone(),
        ], $suppliers);

        return $this->json($data);
    }

    #[Route('/suppliers', name: 'api_supplier_create', methods: ['POST'])]
    public function createSupplier(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        $supplier = new Supplier();
        $supplier->setName($data['name']);
        $supplier->setContactEmail($data['email'] ?? null);
        $supplier->setPhone($data['phone'] ?? null);

        $em->persist($supplier);
        $em->flush();

        return $this->json(['message' => 'Supplier created successfully'], Response::HTTP_CREATED);
    }
}
