<?php

namespace App\Repository;

use App\Entity\Appointment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Appointment>
 */
class AppointmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Appointment::class);
    }

    /**
     * Find appointments for an employee on a given date
     * @return Appointment[]
     */
    public function findByEmployeeAndDate(int $employeeId, \DateTimeInterface $date): array
    {
        $start = \DateTime::createFromInterface($date)->setTime(0, 0);
        $end = (clone $start)->setTime(23, 59, 59);

        return $this->createQueryBuilder('a')
            ->andWhere('a.employee = :employeeId')
            ->andWhere('a.startAt BETWEEN :start AND :end')
            ->andWhere('a.status != :cancelled')
            ->setParameter('employeeId', $employeeId)
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->setParameter('cancelled', Appointment::STATUS_CANCELLED)
            ->orderBy('a.startAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Check if there's an overlap for an employee at a given time range
     */
    public function hasOverlap(int $employeeId, \DateTimeInterface $startAt, \DateTimeInterface $endAt, ?int $excludeId = null): bool
    {
        $qb = $this->createQueryBuilder('a')
            ->select('COUNT(a.id)')
            ->andWhere('a.employee = :employeeId')
            ->andWhere('a.startAt < :endAt')
            ->andWhere('a.endAt > :startAt')
            ->andWhere('a.status != :cancelled')
            ->setParameter('employeeId', $employeeId)
            ->setParameter('startAt', $startAt)
            ->setParameter('endAt', $endAt)
            ->setParameter('cancelled', Appointment::STATUS_CANCELLED);

        if ($excludeId) {
            $qb->andWhere('a.id != :excludeId')
                ->setParameter('excludeId', $excludeId);
        }

        return (int) $qb->getQuery()->getSingleScalarResult() > 0;
    }

    /**
     * Get today's appointments
     * @return Appointment[]
     */
    public function findToday(): array
    {
        $start = new \DateTime('today');
        $end = new \DateTime('today 23:59:59');

        return $this->createQueryBuilder('a')
            ->andWhere('a.startAt BETWEEN :start AND :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->orderBy('a.startAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get upcoming appointments needing reminder (24h before)
     * @return Appointment[]
     */
    public function findNeedingReminder(): array
    {
        $from = new \DateTime('+23 hours');
        $to = new \DateTime('+25 hours');

        return $this->createQueryBuilder('a')
            ->andWhere('a.startAt BETWEEN :from AND :to')
            ->andWhere('a.status IN (:statuses)')
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->setParameter('statuses', [Appointment::STATUS_PENDING, Appointment::STATUS_CONFIRMED])
            ->getQuery()
            ->getResult();
    }

    /**
     * Get revenue stats for a date range
     */
    public function getRevenueStats(\DateTimeInterface $from, \DateTimeInterface $to): array
    {
        return $this->createQueryBuilder('a')
            ->select('a.paymentMethod, COUNT(a.id) as total, SUM(s.price) as revenue')
            ->join('a.service', 's')
            ->andWhere('a.startAt BETWEEN :from AND :to')
            ->andWhere('a.status = :completed')
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->setParameter('completed', Appointment::STATUS_COMPLETED)
            ->groupBy('a.paymentMethod')
            ->getQuery()
            ->getResult();
    }
}
