<?php

namespace App\Service;

use App\Entity\Notification;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class PushService
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    public function sendNotification(User $user, string $title, string $message, string $type = Notification::TYPE_PUSH): void
    {
        $notification = new Notification();
        $notification->setUser($user);
        $notification->setTitle($title);
        $notification->setMessage($message);
        $notification->setType($type);

        $this->em->persist($notification);
        $this->em->flush();
        
        // Logic for external Push SDK would go here
    }
}
