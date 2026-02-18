<?php

namespace App\Service;

use App\Entity\Appointment;
use App\Repository\AppointmentRepository;
use App\Repository\InvoiceRepository;
use App\Repository\UserRepository;

class AnalyticsService
{
    public function __construct(
        private AppointmentRepository $appointmentRepository,
        private InvoiceRepository $invoiceRepository,
        private UserRepository $userRepository
    ) {}

    public function calculateKPIs(\DateTimeInterface $start, \DateTimeInterface $end): array
    {
        $appointments = $this->appointmentRepository->createQueryBuilder('a')
            ->where('a.startAt >= :start')
            ->andWhere('a.startAt <= :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getResult();

        $totalRevenue = 0;
        $completedCount = 0;
        $cancelledCount = 0;

        foreach ($appointments as $appointment) {
            if ($appointment->getStatus() === Appointment::STATUS_COMPLETED) {
                $completedCount++;
                $totalRevenue += $appointment->getService()->getPrice();
            } elseif ($appointment->getStatus() === Appointment::STATUS_CANCELLED) {
                $cancelledCount++;
            }
        }

        $averageTicket = $completedCount > 0 ? $totalRevenue / $completedCount : 0;
        $cancellationRate = count($appointments) > 0 ? ($cancelledCount / count($appointments)) * 100 : 0;

        return [
            'totalRevenue' => round($totalRevenue, 2),
            'completedAppointments' => $completedCount,
            'averageTicket' => round($averageTicket, 2),
            'cancellationRate' => round($cancellationRate, 2),
            'totalAppointments' => count($appointments),
        ];
    }

    public function getRetentionRate(): float
    {
        $totalCustomers = $this->userRepository->count(['roles' => 'ROLE_CUSTOMER']);
        if ($totalCustomers === 0) return 0;

        // Simplified logic: Customers with > 1 completed appointment
        $returningCustomers = $this->appointmentRepository->createQueryBuilder('a')
            ->select('COUNT(DISTINCT a.customer)')
            ->where('a.status = :status')
            ->setParameter('status', Appointment::STATUS_COMPLETED)
            ->groupBy('a.customer')
            ->having('COUNT(a.id) > 1')
            ->getQuery()
            ->getResult();

        return round((count($returningCustomers) / $totalCustomers) * 100, 2);
    }

    public function predictDemand(\DateTimeInterface $date): array
    {
        // Simulated AI: Looks at the day of the week in history
        $dayOfWeek = $date->format('N');
        
        $historicalAppointments = $this->appointmentRepository->createQueryBuilder('a')
            ->select('COUNT(a.id)')
            ->where('DAYOFWEEK(a.startAt) = :day')
            ->setParameter('day', $dayOfWeek)
            ->getQuery()
            ->getSingleScalarResult();

        // Basic weighting: simple average simulation
        $predictedCount = (int) ceil($historicalAppointments / 4); // Assuming 4 weeks of data avg
        
        $confidence = $historicalAppointments > 10 ? 'high' : 'low';

        return [
            'date' => $date->format('Y-m-d'),
            'predictedAppointments' => max($predictedCount, 2), // Min 2 for sanity
            'confidence' => $confidence,
            'occupancyLevel' => $predictedCount > 10 ? 'Hot' : ($predictedCount > 5 ? 'Medium' : 'Light'),
        ];
    }
}
