<?php

namespace App\Controller;

use App\classe\Cart;
use App\Entity\Order;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{
    #[Route('/commande/create-session/{reference}', name: 'stripe_create-session')]
    public function index(EntityManagerInterface $entityManager, Cart $cart, $reference): Response
    {
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';
        $product_for_stripe = [];

        $order = $entityManager->getRepository(Order::class)->findOneByReference($reference);
        if (!$order) {
            new JsonResponse(['error' => 'order']);
        }


        foreach ($order->getOrderDetails()->getValues() as $product) {
            $product_object = $entityManager->getRepository(Product::class)->findOneByName($product->getProduct());
            $product_for_stripe[] = array(
                'price_data' => array(
                    'currency' => 'eur',
                    'unit_amount' => $product->getPrice(),
                    'product_data' => array(
                        'name' => $product->getProduct(),
                        'images' => array($YOUR_DOMAIN . "/uploads/" . $product_object->getIllustration()),
                    ),
                ),
                'quantity' => $product->getQuantity(),
            );
        }
        $product_for_stripe[] = array(
            'price_data' => array(
                'currency' => 'eur',
                'unit_amount' => $order->getCarrierPrice() ,
                'product_data' => array(
                    'name' => $order->getCarrierName(),
                    'images' => array($YOUR_DOMAIN),
                ),
            ),
            'quantity' => $product->getQuantity(),
        );

        Stripe::setApiKey('sk_test_51IT4M4BpsCG1okk4XgDJmDIVF8l2bQiHFtwtMrAz1zFoLfbz9Mu24bq7wCr8U3EqnOLSfhOwOJZHc6hCp0NIUSyn000ElKHvih');
        $checkout_session = Session::create([
            'customer_email'=>$this->getUser()->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => [
                $product_for_stripe
            ],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/commande/success/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/commande/cancel/{CHECKOUT_SESSION_ID}',
        ]);
        $order->setStripeSessionId($checkout_session->id);
        $entityManager->flush();
        $response = new JsonResponse(['id' => $checkout_session->id]);
        return $response;

    }
}
