<?php

namespace App\Controller\Api;

use App\Entity\Achievement;
use App\Entity\User;
use App\Repository\AchievementRepository;
use App\Service\GamificationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/gamification')]
class GamificationController extends AbstractController
{
    #[Route('/achievements', name: 'api_gamification_list', methods: ['GET'])]
    public function listAchievements(AchievementRepository $achievementRepo): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $allAchievements = $achievementRepo->findAll();
        $userEarnedIds = $user->getAchievements()->map(fn($ua) => $ua->getAchievement()->getId())->toArray();

        $data = array_map(fn($a) => [
            'id' => $a->getId(),
            'name' => $a->getName(),
            'description' => $a->getDescription(),
            'icon' => $a->getIcon(),
            'points' => $a->getPointsReward(),
            'isEarned' => in_array($a->getId(), $userEarnedIds),
        ], $allAchievements);

        return $this->json($data);
    }

    #[Route('/check', name: 'api_gamification_check', methods: ['POST'])]
    public function check(GamificationService $gamificationService): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $newOnes = $gamificationService->checkAchievements($user);
        
        return $this->json([
            'newAchievementsCount' => count($newOnes),
            'newAchievements' => array_map(fn($ua) => [
                'name' => $ua->getAchievement()->getName(),
                'pointsEarned' => $ua->getAchievement()->getPointsReward()
            ], $newOnes)
        ]);
    }

    #[Route('/admin/achievements', name: 'api_gamification_admin_create', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function createAchievement(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $achievement = new Achievement();
        $achievement->setName($data['name']);
        $achievement->setDescription($data['description']);
        $achievement->setIcon($data['icon'] ?? 'star');
        $achievement->setPointsReward($data['points'] ?? 10);

        $em->persist($achievement);
        $em->flush();

        return $this->json(['message' => 'Achievement template created'], Response::HTTP_CREATED);
    }
}
