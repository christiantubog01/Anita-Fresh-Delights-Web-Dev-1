<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Repository\ProductRepository;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(
        SessionInterface $session,
        ProductRepository $productRepository
    ): Response {

        $cart = $session->get('cart', []);

        $cartData = [];

        $total = 0;

        foreach ($cart as $id => $quantity) {

            $product = $productRepository->find($id);

            if (!$product) {
                continue;
            }

            $subtotal = $product->getPrice() * $quantity;

            $cartData[] = [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $subtotal
            ];

            $total += $subtotal;
        }

        return $this->render('cart/index.html.twig', [
            'cartItems' => $cartData,
            'total' => $total
        ]);
    }

    #[Route('/cart/add/{id}', name: 'app_cart_add', methods: ['POST'])]
    public function add(
        Product $product,
        Request $request,
        SessionInterface $session
    ): Response {

        $quantity = (int) $request->request->get('quantity', 1);

        if ($quantity < 1) {
            $quantity = 1;
        }

        $cart = $session->get('cart', []);

        $id = $product->getId();

        if (isset($cart[$id])) {

            $cart[$id] += $quantity;

        } else {

            $cart[$id] = $quantity;
        }

        $session->set('cart', $cart);

        $this->addFlash('success', 'Product added to cart.');

        return $this->redirectToRoute('app_all_products');
    }

    #[Route('/cart/remove/{id}', name: 'app_cart_remove')]
    public function remove(
        int $id,
        SessionInterface $session
    ): Response {

        $cart = $session->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart');
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/cart/checkout', name: 'app_cart_checkout')]
    public function checkout(
        SessionInterface $session,
        ProductRepository $productRepository,
        EntityManagerInterface $entityManager
    ): Response {

        $cart = $session->get('cart', []);

        if (empty($cart)) {

            $this->addFlash('error', 'Your cart is empty.');

            return $this->redirectToRoute('app_cart');
        }

        $order = new Order();

        $order->setUser($this->getUser());

        $entityManager->persist($order);

        $total = 0;

        foreach ($cart as $id => $quantity) {

            $product = $productRepository->find($id);

            if (!$product) {
                continue;
            }

            $stock = $product->getStock();

            if (!$stock) {

                $this->addFlash(
                    'error',
                    $product->getProductName() . ' has no stock assigned.'
                );

                return $this->redirectToRoute('app_cart');
            }

            if ($quantity > $stock->getQuantity()) {

                $this->addFlash(
                    'error',
                    'Not enough stock for ' . $product->getProductName()
                );

                return $this->redirectToRoute('app_cart');
            }

            $subtotal = $product->getPrice() * $quantity;

            $orderItem = new OrderItem();

            $orderItem->setOrderRef($order);
            $orderItem->setProduct($product);
            $orderItem->setQuantity($quantity);
            $orderItem->setPrice($product->getPrice());
            $orderItem->setSubtotal($subtotal);

            $entityManager->persist($orderItem);

            // deduct stock
            $newQuantity = $stock->getQuantity() - $quantity;

            $stock->setQuantity($newQuantity);

            $total += $subtotal;
        }

        $order->setTotal($total);

        $entityManager->flush();

        $session->remove('cart');

        $this->addFlash('success', 'Order placed successfully.');

        return $this->redirectToRoute('app_all_products');
    }
}