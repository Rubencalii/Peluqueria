<?php

namespace App\Entity;

use App\Repository\LoyaltyPointsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LoyaltyPointsRepository::class)]
class LoyaltyPoints
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $points = 0;

    #[ORM\Column]
    private ?\DateTimeImmutable $lastUpdated = null;

    #[ORM\OneToOne(inversedBy: 'loyaltyPoints', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $customer = null;

    public function __construct()
    {
        $this->lastUpdated = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(int $points): static
    {
        $this->points = $points;
        $this->lastUpdated = new \DateTimeImmutable();
        return $this;
    }

    public function addPoints(int $points): static
    {
        $this->points += $points;
        $this->lastUpdated = new \DateTimeImmutable();
        return $this;
    }

    public function getLastUpdated(): ?\DateTimeImmutable
    {
        return $this->lastUpdated;
    }

    public function setLastUpdated(\DateTimeImmutable $lastUpdated): static
    {
        $this->lastUpdated = $lastUpdated;
        return $this;
    }

    public function getCustomer(): ?User
    {
        return $this->customer;
    }

    public function setCustomer(User $customer): static
    {
        $this->customer = $customer;
        return $this;
    }
}
