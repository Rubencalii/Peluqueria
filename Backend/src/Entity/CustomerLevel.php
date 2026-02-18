<?php

namespace App\Entity;

use App\Repository\CustomerLevelRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomerLevelRepository::class)]
class CustomerLevel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $minPoints = null;

    #[ORM\Column(nullable: true)]
    private ?array $benefits = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getMinPoints(): ?int
    {
        return $this->minPoints;
    }

    public function setMinPoints(int $minPoints): static
    {
        $this->minPoints = $minPoints;
        return $this;
    }

    public function getBenefits(): ?array
    {
        return $this->benefits;
    }

    public function setBenefits(?array $benefits): static
    {
        $this->benefits = $benefits;
        return $this;
    }
}
