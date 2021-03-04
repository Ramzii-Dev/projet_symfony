<?php

namespace App\Controller;

use App\classe\Cart;
use App\Form\OrderType;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/commande', name: 'order')]
    public function index(Cart $cart): Response

    {

       if(!$this->getUser()->getAddresses()->getValues())
       {
           return $this->redirectToRoute('account_address_add');
       }

        $form = $this->createForm(OrderType::class,null,[
            'user'=>$this->getUser()
        ]);
        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            $cart->getFull()
        ]);
    }
}
