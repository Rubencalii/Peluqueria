<?php

namespace App\Service;

use App\Entity\Appointment;
use App\Entity\Invoice;
use App\Repository\InvoiceRepository;
use Doctrine\ORM\EntityManagerInterface;

class FinanceService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private InvoiceRepository $invoiceRepository
    ) {}

    public function generateInvoiceForAppointment(Appointment $appointment): Invoice
    {
        if ($appointment->getInvoice()) {
            return $appointment->getInvoice();
        }

        $service = $appointment->getService();
        $total = $service->getPrice();
        
        // Basic VAT calculation (21%)
        $taxRate = 0.21;
        $amount = $total / (1 + $taxRate);
        $taxAmount = $total - $amount;

        $invoice = new Invoice();
        $invoice->setAmount(round($amount, 2));
        $invoice->setTaxAmount(round($taxAmount, 2));
        $invoice->setTotalAmount(round($total, 2));
        $invoice->setAppointment($appointment);
        
        // Generate a sequential-like number
        $lastInvoice = $this->invoiceRepository->findOneBy([], ['id' => 'DESC']);
        $nextId = $lastInvoice ? $lastInvoice->getId() + 1 : 1;
        $invoice->setNumber('INV-' . date('Y') . '-' . str_pad((string)$nextId, 5, '0', STR_PAD_LEFT));

        $this->entityManager->persist($invoice);
        $this->entityManager->flush();

        return $invoice;
    }

    public function getFinancialStats(): array
    {
        $invoices = $this->invoiceRepository->findAll();
        
        $totalRevenue = 0;
        $totalVAT = 0;
        
        foreach ($invoices as $invoice) {
            $totalRevenue += $invoice->getTotalAmount();
            $totalVAT += $invoice->getTaxAmount();
        }

        return [
            'totalRevenue' => $totalRevenue,
            'totalVAT' => $totalVAT,
            'invoiceCount' => count($invoices),
        ];
    }
}
