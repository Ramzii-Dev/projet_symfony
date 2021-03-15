<?php

namespace App\Controller;

use App\classe\Cart;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderSuccessController extends AbstractController
{
    private  $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/commande/success/{stripeSessionId}', name: 'order_success')]
    public function index(Cart $cart, $stripeSessionId): Response
    {
       $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);

        if(!$order || $order->getUser() != $this->getUser()){
            $this->redirectToRoute('home');
        }
        header( "refresh:5;url=http://127.0.0.1:8000/" );
        if (!$order->getIsPaid()){
            $order->setIsPaid(1);
            $cart->remove();
            $this->entityManager->flush();
            //envoyer un mail de confirmation
        }

        return $this->render('order_success/index.html.twig',[
            'order'=>$order
            ]);
    }
}
