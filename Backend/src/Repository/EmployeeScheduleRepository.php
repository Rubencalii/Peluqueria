<?php

namespace App\Repository;

use App\Entity\EmployeeSchedule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EmployeeSchedule>
 */
class EmployeeScheduleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmployeeSchedule::class);
    }

    /**
     * Get the schedule for an employee on a given day of the week
     */
    public function findByEmployeeAndDay(int $employeeId, int $dayOfWeek): ?EmployeeSchedule
    {
        return $this->createQueryBuilder('es')
            ->andWhere('es.employee = :employeeId')
            ->andWhere('es.dayOfWeek = :day')
            ->andWhere('es.isActive = :active')
            ->setParameter('employeeId', $employeeId)
            ->setParameter('day', $dayOfWeek)
            ->setParameter('active', true)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
