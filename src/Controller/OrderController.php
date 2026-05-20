<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use App\Entity\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class OrderController extends AbstractController
{
    #[Route('/my-orders', name: 'app_my_orders')]
    public function index(
        OrderRepository $orderRepository
    ): Response {
        
        $user = $this->getUser();

        // If there is no logged in user, redirect to login
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // If the user exists but is not verified, redirect to the verification notice
        if (method_exists($user, 'isVerified') && !$user->isVerified()) {
            return $this->redirectToRoute('app_verify_notice');
        }

        $orders = $orderRepository->findBy(
            ['user' => $this->getUser()],
            ['created_at' => 'DESC']
        );

        return $this->render('order/index.html.twig', [
            'orders' => $orders
        ]);
    }

#[Route('/my-orders/{id}', name: 'app_order_show')]
public function show(
    \App\Entity\Order $order
): Response {

    if ($order->getUser() !== $this->getUser()) {

        throw $this->createAccessDeniedException();
    }

    return $this->render('order/show.html.twig', [
        'order' => $order
    ]);
}
}