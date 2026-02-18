<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\StockMovement;
use Doctrine\ORM\EntityManagerInterface;

class InventoryService
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function adjustStock(Product $product, int $quantity, string $type, string $reason): StockMovement
    {
        if ($type === StockMovement::TYPE_IN) {
            $product->addStock($quantity);
        } else {
            $product->removeStock($quantity);
        }

        $movement = new StockMovement();
        $movement->setProduct($product);
        $movement->setQuantity($quantity);
        $movement->setType($type);
        $movement->setReason($reason);

        $this->entityManager->persist($movement);
        $this->entityManager->flush();

        return $movement;
    }

    public function getInventoryAlerts(array $products): array
    {
        return array_filter($products, fn($p) => $p->isLowStock());
    }
}
