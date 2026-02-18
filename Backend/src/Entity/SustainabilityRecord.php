<?php

namespace App\Entity;

use App\Repository\SustainabilityRecordRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SustainabilityRecordRepository::class)]
class SustainabilityRecord
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Appointment $appointment = null;

    #[ORM\Column]
    private ?float $carbonSaved = 0.0; // In grams

    #[ORM\Column]
    private ?float $waterSaved = 0.0; // In liters

    #[ORM\Column]
    private ?float $donationAmount = 0.0;

    #[ORM\Column]
    private ?\DateTimeImmutable $recordedAt = null;

    public function __construct()
    {
        $this->recordedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAppointment(): ?Appointment
    {
        return $this->appointment;
    }

    public function setAppointment(?Appointment $appointment): static
    {
        $this->appointment = $appointment;
        return $this;
    }

    public function getCarbonSaved(): ?float
    {
        return $this->carbonSaved;
    }

    public function setCarbonSaved(float $carbonSaved): static
    {
        $this->carbonSaved = $carbonSaved;
        return $this;
    }

    public function getWaterSaved(): ?float
    {
        return $this->waterSaved;
    }

    public function setWaterSaved(float $waterSaved): static
    {
        $this->waterSaved = $waterSaved;
        return $this;
    }

    public function getDonationAmount(): ?float
    {
        return $this->donationAmount;
    }

    public function setDonationAmount(float $donationAmount): static
    {
        $this->donationAmount = $donationAmount;
        return $this;
    }

    public function getRecordedAt(): ?\DateTimeImmutable
    {
        return $this->recordedAt;
    }
}
