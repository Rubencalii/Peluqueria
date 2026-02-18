<?php

namespace App\Entity;

use App\Repository\BeforeAfterGalleryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BeforeAfterGalleryRepository::class)]
class BeforeAfterGallery
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $beforePhotoUrl = null;

    #[ORM\Column(length: 255)]
    private ?string $afterPhotoUrl = null;

    #[ORM\Column]
    private ?bool $consent = false;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne]
    private ?Service $service = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $employee = null;

    #[ORM\ManyToOne(inversedBy: 'galleryPhotos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $customer = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBeforePhotoUrl(): ?string
    {
        return $this->beforePhotoUrl;
    }

    public function setBeforePhotoUrl(string $beforePhotoUrl): static
    {
        $this->beforePhotoUrl = $beforePhotoUrl;
        return $this;
    }

    public function getAfterPhotoUrl(): ?string
    {
        return $this->afterPhotoUrl;
    }

    public function setAfterPhotoUrl(string $afterPhotoUrl): static
    {
        $this->afterPhotoUrl = $afterPhotoUrl;
        return $this;
    }

    public function isConsent(): ?bool
    {
        return $this->consent;
    }

    public function setConsent(bool $consent): static
    {
        $this->consent = $consent;
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

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): static
    {
        $this->service = $service;
        return $this;
    }

    public function getEmployee(): ?User
    {
        return $this->employee;
    }

    public function setEmployee(?User $employee): static
    {
        $this->employee = $employee;
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
}
