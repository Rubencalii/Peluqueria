<?php

namespace App\Controller\Api;

use App\Entity\BeforeAfterGallery;
use App\Entity\User;
use App\Repository\BeforeAfterGalleryRepository;
use App\Repository\MarketingCampaignRepository;
use App\Repository\ServiceRepository;
use App\Repository\UserRepository;
use App\Service\MarketingService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/marketing')]
class MarketingController extends AbstractController
{
    #[Route('/gallery', name: 'api_gallery_list', methods: ['GET'])]
    public function getGallery(BeforeAfterGalleryRepository $galleryRepo): JsonResponse
    {
        $photos = $galleryRepo->findBy(['consent' => true], ['createdAt' => 'DESC']);
        $data = array_map(fn($p) => [
            'id' => $p->getId(),
            'beforePhotoUrl' => $p->getBeforePhotoUrl(),
            'afterPhotoUrl' => $p->getAfterPhotoUrl(),
            'serviceName' => $p->getService()?->getName(),
            'professionalName' => $p->getEmployee()?->getName(),
        ], $photos);

        return $this->json($data);
    }

    #[Route('/gallery', name: 'api_gallery_upload', methods: ['POST'])]
    #[IsGranted('ROLE_EMPLOYEE')]
    public function uploadGalleryPhoto(
        Request $request,
        UserRepository $userRepo,
        ServiceRepository $serviceRepo,
        EntityManagerInterface $em
    ): JsonResponse {
        /** @var User $employee */
        $employee = $this->getUser();
        $data = json_decode($request->getContent(), true);

        $customer = $userRepo->find($data['customerId'] ?? 0);
        $service = $serviceRepo->find($data['serviceId'] ?? 0);

        if (!$customer) {
            return $this->json(['error' => 'Customer not found'], Response::HTTP_NOT_FOUND);
        }

        $gallery = new BeforeAfterGallery();
        $gallery->setBeforePhotoUrl($data['beforePhotoUrl']);
        $gallery->setAfterPhotoUrl($data['afterPhotoUrl']);
        $gallery->setConsent($data['consent'] ?? false);
        $gallery->setCustomer($customer);
        $gallery->setEmployee($employee);
        $gallery->setService($service);

        $em->persist($gallery);
        $em->flush();

        return $this->json(['message' => 'Gallery photo uploaded successfully'], Response::HTTP_CREATED);
    }

    #[Route('/referral', name: 'api_referral_info', methods: ['GET'])]
    public function getReferral(MarketingService $marketingService): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $referral = $user->getReferralCode();
        if (!$referral) {
            $referral = $marketingService->generateReferralCode($user);
        }

        return $this->json([
            'code' => $referral->getCode(),
            'usageCount' => $referral->getUsageCount(),
        ]);
    }

    #[Route('/campaigns', name: 'api_campaigns_list', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function getCampaigns(MarketingCampaignRepository $campaignRepo): JsonResponse
    {
        $campaigns = $campaignRepo->findAll();
        $data = array_map(fn($c) => [
            'id' => $c->getId(),
            'name' => $c->getName(),
            'type' => $c->getType(),
            'active' => $c->isActive(),
        ], $campaigns);

        return $this->json($data);
    }
}
