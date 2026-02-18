<?php

namespace App\Service;

use App\Entity\Achievement;
use App\Entity\User;
use App\Entity\UserAchievement;
use App\Repository\AchievementRepository;
use App\Repository\UserAchievementRepository;
use Doctrine\ORM\EntityManagerInterface;

class GamificationService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private AchievementRepository $achievementRepository,
        private UserAchievementRepository $userAchievementRepository
    ) {}

    public function checkAchievements(User $user): array
    {
        $newAchievements = [];
        $earnedIds = $user->getAchievements()->map(fn($ua) => $ua->getAchievement()->getId())->toArray();

        // 1. "Pionero": First appointment
        if (!in_array(1, $earnedIds) && count($user->getAppointmentsAsCustomer()) >= 1) {
            $achievement = $this->achievementRepository->find(1);
            if ($achievement) {
                $newAchievements[] = $this->awardAchievement($user, $achievement);
            }
        }

        // 2. "Fan Incondicional": 5 appointments
        if (!in_array(2, $earnedIds) && count($user->getAppointmentsAsCustomer()) >= 5) {
            $achievement = $this->achievementRepository->find(2);
            if ($achievement) {
                $newAchievements[] = $this->awardAchievement($user, $achievement);
            }
        }

        // 3. "Crítico de Peluquería": 3 reviews
        if (!in_array(3, $earnedIds) && count($user->getReviews()) >= 3) {
            $achievement = $this->achievementRepository->find(3);
            if ($achievement) {
                $newAchievements[] = $this->awardAchievement($user, $achievement);
            }
        }

        return $newAchievements;
    }

    private function awardAchievement(User $user, Achievement $achievement): UserAchievement
    {
        $userAchievement = new UserAchievement();
        $userAchievement->setUser($user);
        $userAchievement->setAchievement($achievement);

        $this->entityManager->persist($userAchievement);
        
        // Reward with loyalty points
        $loyaltyPoints = $user->getLoyaltyPoints();
        if ($loyaltyPoints) {
            $loyaltyPoints->addPoints($achievement->getPointsReward());
        }

        $this->entityManager->flush();

        return $userAchievement;
    }
}
