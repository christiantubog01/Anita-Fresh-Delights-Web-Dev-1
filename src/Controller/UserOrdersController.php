<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\OrderRepository;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserOrdersController extends AbstractController
{
    #[Route('/dashboard/orders', name: 'app_dashboard_orders_index')]
    public function index(
        OrderRepository $orderRepository
    ): Response
    {
        $orders = $orderRepository->findBy(
            [],
            ['created_at' => 'DESC']
        );

        return $this->render('user_orders/index.html.twig', [
            'orders' => $orders
        ]);
    }

    #[Route('/dashboard/orders/status/{id}/{status}', name: 'app_dashboard_order_status')]
    public function updateStatus(
        Order $order,
        string $status,
        EntityManagerInterface $entityManager
    ): Response
    {
        $allowedStatuses = [
            'Pending',
            'Processing',
            'Completed',
            'Cancelled'
        ];

        if (!in_array($status, $allowedStatuses)) {
            throw $this->createNotFoundException();
        }

        $order->setStatus($status);

        $entityManager->flush();

        $this->addFlash(
            'success',
            'Order status updated successfully.'
        );

        return $this->redirectToRoute('app_dashboard_orders_index');
    }

    #[Route('/dashboard/orders/{id}', name: 'app_dashboard_order_show')]
    public function show(
        Order $order
    ): Response
    {
        return $this->render('user_orders/show.html.twig', [
            'order' => $order
        ]);
    }
}