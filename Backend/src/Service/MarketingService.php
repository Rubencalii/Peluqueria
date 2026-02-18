<?php

namespace App\Service;

use App\Entity\ReferralCode;
use App\Entity\User;
use App\Repository\ReferralCodeRepository;
use Doctrine\ORM\EntityManagerInterface;

class MarketingService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ReferralCodeRepository $referralCodeRepository
    ) {}

    public function generateReferralCode(User $user): ReferralCode
    {
        if ($user->getReferralCode()) {
            return $user->getReferralCode();
        }

        $code = strtoupper(substr($user->getName(), 0, 3) . rand(100, 999));
        
        // Ensure uniqueness (basic check)
        while ($this->referralCodeRepository->findOneBy(['code' => $code])) {
            $code = strtoupper(substr($user->getName(), 0, 3) . rand(100, 999));
        }

        $referralCode = new ReferralCode();
        $referralCode->setCode($code);
        $referralCode->setOwner($user);

        $this->entityManager->persist($referralCode);
        $this->entityManager->flush();

        return $referralCode;
    }

    public function applyReferral(string $code): ?User
    {
        $referralCode = $this->referralCodeRepository->findOneBy(['code' => $code]);
        if (!$referralCode) {
            return null;
        }

        $referralCode->incrementUsage();
        $this->entityManager->flush();

        return $referralCode->getOwner();
    }
}
