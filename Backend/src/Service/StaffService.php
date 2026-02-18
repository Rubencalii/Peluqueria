<?php

namespace App\Service;

use App\Entity\Appointment;
use App\Entity\User;
use App\Repository\AppointmentRepository;

class StaffService
{
    public function __construct(
        private AppointmentRepository $appointmentRepository
    ) {}

    public function calculateCommissions(User $employee, \DateTimeInterface $start, \DateTimeInterface $end): array
    {
        if (!$employee->isEmployee() && !$employee->isAdmin()) {
            return ['total' => 0, 'details' => []];
        }

        $rate = $employee->getCommissionRate() ?? 0;
        $appointments = $this->appointmentRepository->createQueryBuilder('a')
            ->where('a.employee = :employee')
            ->andWhere('a.status = :status')
            ->andWhere('a.startAt >= :start')
            ->andWhere('a.startAt <= :end')
            ->setParameter('employee', $employee)
            ->setParameter('status', Appointment::STATUS_COMPLETED)
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getResult();

        $totalCommission = 0;
        $details = [];

        foreach ($appointments as $appointment) {
            $servicePrice = $appointment->getService()->getPrice();
            $commission = $servicePrice * $rate;
            $totalCommission += $commission;
            
            $details[] = [
                'appointmentId' => $appointment->getId(),
                'date' => $appointment->getStartAt()->format('Y-m-d H:i'),
                'service' => $appointment->getService()->getName(),
                'price' => $servicePrice,
                'commission' => round($commission, 2)
            ];
        }

        return [
            'employee' => $employee->getFullName(),
            'period' => [
                'start' => $start->format('Y-m-d'),
                'end' => $end->format('Y-m-d'),
            ],
            'rate' => $rate * 100 . '%',
            'baseSalary' => $employee->getBaseSalary(),
            'totalCommissions' => round($totalCommission, 2),
            'totalEarnings' => round(($employee->getBaseSalary() ?? 0) + $totalCommission, 2),
            'appointmentsCount' => count($appointments),
            'details' => $details
        ];
    }
}
