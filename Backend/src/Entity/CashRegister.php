<?php

namespace App\Entity;

use App\Repository\CashRegisterRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CashRegisterRepository::class)]
class CashRegister
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?string $openingBalance = '0.00';

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?string $closingBalance = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?string $expectedBalance = '0.00';

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?string $totalCard = '0.00';

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?string $totalCash = '0.00';

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?string $totalOnline = '0.00';

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $notes = null;

    public function __construct()
    {
        $this->date = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;
        return $this;
    }

    public function getOpeningBalance(): ?string
    {
        return $this->openingBalance;
    }

    public function setOpeningBalance(string $openingBalance): static
    {
        $this->openingBalance = $openingBalance;
        return $this;
    }

    public function getClosingBalance(): ?string
    {
        return $this->closingBalance;
    }

    public function setClosingBalance(string $closingBalance): static
    {
        $this->closingBalance = $closingBalance;
        return $this;
    }

    public function getExpectedBalance(): ?string
    {
        return $this->expectedBalance;
    }

    public function setExpectedBalance(string $expectedBalance): static
    {
        $this->expectedBalance = $expectedBalance;
        return $this;
    }

    public function getTotalCard(): ?string
    {
        return $this->totalCard;
    }

    public function setTotalCard(string $totalCard): static
    {
        $this->totalCard = $totalCard;
        return $this;
    }

    public function getTotalCash(): ?string
    {
        return $this->totalCash;
    }

    public function setTotalCash(string $totalCash): static
    {
        $this->totalCash = $totalCash;
        return $this;
    }

    public function getTotalOnline(): ?string
    {
        return $this->totalOnline;
    }

    public function setTotalOnline(string $totalOnline): static
    {
        $this->totalOnline = $totalOnline;
        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;
        return $this;
    }
}
