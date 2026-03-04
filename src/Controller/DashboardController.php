<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\StockRepository;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use App\Repository\ActivityLogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(
        ProductRepository $productRepo,
        StockRepository $stockRepo,
        CategoryRepository $categoryRepo,
        UserRepository $userRepo,
        ActivityLogRepository $activityLogRepo
    ): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'productCount' => $productRepo->count([]),
            'stockCount' => $stockRepo->count([]),
            'categoryCount' => $categoryRepo->count([]),
            'userCount' => $userRepo->count([]),
            'categories' => $categoryRepo->findAll(),

            // ✅ ONLY 5 latest logs
            'activityLogs' => $activityLogRepo->findBy(
                [],
                ['createdAt' => 'DESC'],
                5
            ),
        ]);
    }
}