<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductsController extends AbstractController
{
    #[Route('/all_products', name: 'app_all_products')]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('products/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }
}
