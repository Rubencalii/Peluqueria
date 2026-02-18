<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column]
    private ?int $stock = 0;

    #[ORM\Column]
    private ?int $minStockAlert = 5;

    #[ORM\Column(length: 100)]
    private ?string $category = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    /** @var Collection<int, StockMovement> */
    #[ORM\OneToMany(targetEntity: StockMovement::class, mappedBy: 'product', orphanRemoval: true)]
    private Collection $stockMovements;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->stockMovements = new ArrayCollection();
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;
        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;
        return $this;
    }

    public function getMinStockAlert(): ?int
    {
        return $this->minStockAlert;
    }

    public function setMinStockAlert(int $minStockAlert): static
    {
        $this->minStockAlert = $minStockAlert;
        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /** @return Collection<int, StockMovement> */
    public function getStockMovements(): Collection
    {
        return $this->stockMovements;
    }

    public function addStock(int $quantity): void
    {
        $this->stock += $quantity;
    }

    public function removeStock(int $quantity): void
    {
        $this->stock -= $quantity;
    }

    public function isLowStock(): bool
    {
        return $this->stock <= $this->minStockAlert;
    }
}
