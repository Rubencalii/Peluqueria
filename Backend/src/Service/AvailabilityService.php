<?php

namespace App\Service;

use App\Entity\Appointment;
use App\Entity\User;
use App\Repository\AppointmentRepository;
use App\Repository\EmployeeScheduleRepository;

class AvailabilityService
{
    public function __construct(
        private AppointmentRepository $appointmentRepo,
        private EmployeeScheduleRepository $scheduleRepo,
    ) {}

    /**
     * Get available time slots for an employee on a given date
     * @return array<array{start: string, end: string}>
     */
    public function getAvailableSlots(User $employee, \DateTimeInterface $date, int $serviceDuration): array
    {
        $dayOfWeek = (int) $date->format('w'); // 0=Sunday...6=Saturday

        // Check if employee works this day
        $schedule = $this->scheduleRepo->findByEmployeeAndDay($employee->getId(), $dayOfWeek);
        if (!$schedule) {
            return [];
        }

        $workStart = $schedule->getStartTime();
        $workEnd = $schedule->getEndTime();

        // Get existing appointments for this employee on this date
        $appointments = $this->appointmentRepo->findByEmployeeAndDate($employee->getId(), $date);

        // Build occupied intervals
        $occupied = [];
        foreach ($appointments as $appt) {
            $occupied[] = [
                'start' => $appt->getStartAt(),
                'end' => $appt->getEndAt(),
            ];
        }

        // Generate slots with 15-minute intervals
        $slots = [];
        $slotInterval = 15; // minutes between slot starts
        $dateStr = $date->format('Y-m-d');

        $current = new \DateTime($dateStr . ' ' . $workStart->format('H:i'));
        $dayEnd = new \DateTime($dateStr . ' ' . $workEnd->format('H:i'));

        while ($current < $dayEnd) {
            $slotEnd = (clone $current)->modify('+' . $serviceDuration . ' minutes');

            // Slot must end before work day ends
            if ($slotEnd > $dayEnd) {
                break;
            }

            // Check overlap with existing appointments
            $isAvailable = true;
            foreach ($occupied as $occ) {
                if ($current < $occ['end'] && $slotEnd > $occ['start']) {
                    $isAvailable = false;
                    break;
                }
            }

            if ($isAvailable) {
                $slots[] = [
                    'start' => $current->format('H:i'),
                    'end' => $slotEnd->format('H:i'),
                ];
            }

            $current->modify('+' . $slotInterval . ' minutes');
        }

        return $slots;
    }
}
