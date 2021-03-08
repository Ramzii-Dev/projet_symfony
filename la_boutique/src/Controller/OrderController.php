<?php

namespace App\Controller;

use App\classe\Cart;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Integer;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class OrderController extends AbstractController
{
    private $entityManger;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManger = $entityManager;
    }

    #[Route('/commande', name: 'order')]
    public function index(Cart $cart , Request $request ): Response

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
            'cart'=>$cart->getFull()
        ]);
    }

    #[Route('/commande/recapitulatif', name: 'order_recap',methods:['POST'])]
    public function add(Cart $cart , Request $request ): Response

    {

        $form = $this->createForm(OrderType::class,null,[
            'user'=>$this->getUser()
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $date = new \DateTime();
            $carriers = $form->get('carriers')->getData();
            $delivery = $form->get('addresses')->getData();
            $delivery_content = $delivery->getFirstname().' '.$delivery->getLastname();
            $delivery_content .= "<br/>".$delivery->getMobile();
            $delivery_content .= "<br/>".$delivery->getPhone();
            if($delivery->getCompany()){
                $delivery_content .= "<br/>".$delivery->getCompany();
            }
            $delivery_content .= "<br/>".$delivery->getName();
            $delivery_content .= "<br/>".$delivery->getPostal().' '.$delivery->getCity();
            $delivery_content .= "<br/>".$delivery->getCountry();



                //enregistrer ma commande order()
            $order = new Order();
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setCarrierName($carriers->getName());
            $order->setCarrierPrice($carriers->getPrice());
            $order->setDelivery($delivery_content);
            $order->setIsPaid(0);
            $this->entityManger->persist($order);

            //enregisterer mes produits orderDetails()

            foreach ($cart->getFull() as $product){
            $orderDetails = new OrderDetails();
            $orderDetails->setMyOrder($order);
            $orderDetails->setProduct($product['product']->getName());
            $orderDetails->setQuantity($product['quantity']);
            $orderDetails->setPrice($product['product']->getPrice());
            $orderDetails->setTotal($product['product']->getPrice() );
            $orderDetails->setTotal($product['product']->getPrice() * $product['quantity']);
            $this->entityManger->persist($orderDetails);
            }


            $this->entityManger->flush();
            return $this->render('order/add.html.twig', [
                'cart'=>$cart->getFull(),
                'carrier'=>$carriers,
                'delivery'=>$delivery_content ]);
        }

       return $this->redirectToRoute('cart');
    }

}
