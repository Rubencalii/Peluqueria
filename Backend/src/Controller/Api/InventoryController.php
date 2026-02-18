<?php

namespace App\Controller\Api;

use App\Entity\Product;
use App\Entity\StockMovement;
use App\Repository\ProductRepository;
use App\Service\InventoryService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/inventory')]
#[IsGranted('ROLE_EMPLOYEE')]
class InventoryController extends AbstractController
{
    #[Route('/products', name: 'api_inventory_list', methods: ['GET'])]
    public function list(ProductRepository $productRepo): JsonResponse
    {
        $products = $productRepo->findAll();
        $data = array_map(fn($p) => [
            'id' => $p->getId(),
            'name' => $p->getName(),
            'price' => $p->getPrice(),
            'stock' => $p->getStock(),
            'category' => $p->getCategory(),
            'isLowStock' => $p->isLowStock(),
        ], $products);

        return $this->json($data);
    }

    #[Route('/products', name: 'api_inventory_create', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        $product = new Product();
        $product->setName($data['name']);
        $product->setPrice($data['price']);
        $product->setCategory($data['category'] ?? 'General');
        $product->setStock($data['initialStock'] ?? 0);
        $product->setMinStockAlert($data['minStock'] ?? 5);

        $em->persist($product);
        $em->flush();

        return $this->json(['message' => 'Product created'], Response::HTTP_CREATED);
    }

    #[Route('/stock-adjust/{id}', name: 'api_inventory_adjust', methods: ['POST'])]
    public function adjust(Product $product, Request $request, InventoryService $inventoryService): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        $movement = $inventoryService->adjustStock(
            $product,
            $data['quantity'],
            $data['type'],
            $data['reason']
        );

        return $this->json([
            'message' => 'Stock adjusted',
            'newStock' => $product->getStock(),
        ]);
    }
}
