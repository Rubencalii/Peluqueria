<?php

namespace App\Service;

use App\Entity\Appointment;
use App\Entity\LoyaltyPoints;
use App\Entity\User;
use App\Repository\CustomerLevelRepository;
use Doctrine\ORM\EntityManagerInterface;

class LoyaltyManager
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CustomerLevelRepository $levelRepository
    ) {}

    public function awardPointsForAppointment(Appointment $appointment): void
    {
        if ($appointment->getStatus() !== Appointment::STATUS_COMPLETED) {
            return;
        }

        $customer = $appointment->getCustomer();
        $service = $appointment->getService();

        if (!$customer || !$service) {
            return;
        }

        // Logic: 1 point for every 10 euros spent
        $pointsToAward = (int) floor($service->getPrice() / 10);

        if ($pointsToAward <= 0) {
            $pointsToAward = 1; // Minimum 1 point for any service
        }

        $loyaltyPoints = $customer->getLoyaltyPoints();
        if (!$loyaltyPoints) {
            $loyaltyPoints = new LoyaltyPoints();
            $loyaltyPoints->setCustomer($customer);
            $this->entityManager->persist($loyaltyPoints);
        }

        $loyaltyPoints->addPoints($pointsToAward);
        
        $this->entityManager->flush();
    }

    public function getCustomerLevel(User $user): string
    {
        $points = $user->getLoyaltyPoints()?->getPoints() ?? 0;
        $levels = $this->levelRepository->findBy([], ['minPoints' => 'DESC']);

        foreach ($levels as $level) {
            if ($points >= $level->getMinPoints()) {
                return $level->getName();
            }
        }

        return 'Bronce'; // Default level
    }
}
