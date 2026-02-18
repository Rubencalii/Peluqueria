<?php

namespace App\Entity;

use App\Repository\WaitingListRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WaitingListRepository::class)]
class WaitingList
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $preferredDate = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $preferredTimeRange = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $customer = null;

    #[ORM\ManyToOne]
    private ?Service $service = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPreferredDate(): ?\DateTimeImmutable
    {
        return $this->preferredDate;
    }

    public function setPreferredDate(\DateTimeImmutable $preferredDate): static
    {
        $this->preferredDate = $preferredDate;
        return $this;
    }

    public function getPreferredTimeRange(): ?string
    {
        return $this->preferredTimeRange;
    }

    public function setPreferredTimeRange(?string $preferredTimeRange): static
    {
        $this->preferredTimeRange = $preferredTimeRange;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getCustomer(): ?User
    {
        return $this->customer;
    }

    public function setCustomer(?User $customer): static
    {
        $this->customer = $customer;
        return $this;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): static
    {
        $this->service = $service;
        return $this;
    }
}
