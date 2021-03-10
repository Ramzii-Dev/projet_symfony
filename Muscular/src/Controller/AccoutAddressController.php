<?php

namespace App\Controller;

use App\classe\Cart;
use App\Entity\Address;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccoutAddressController extends AbstractController
{
    private $entityManger;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManger  = $entityManager;
    }

    #[Route('/compte/addresse', name: 'account_address')]
    public function index(): Response
    {
        return $this->render('account/address.html.twig');
    }

    #[Route('/compte/ajouter-addresse', name: 'account_address_add')]
    public function add(Cart $cart,Request $request): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class,$address);
        $form->handleRequest($request);
        if($form->isSubmitted() &&  $form->isValid()){
            $address->setUser($this->getUser());
            $this->entityManger->persist($address);
            $this->entityManger->flush();
            if($cart->get())
            {
            return $this->redirectToRoute('order');
            }else
                {
            return $this->redirectToRoute('account_address');
            }

        }
        return $this->render('account/address_add.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    #[Route('/compte/modifier-addresse/{id}', name: 'account_address_edit')]
    public function edit(Request $request ,$id): Response
    {
        $address = $this->entityManger->getRepository(Address::class)->findOneById($id);
        if (!$address || $address->getUser() != $this->getUser()){
            return $this->redirectToRoute('account_address');
        }
        $form = $this->createForm(AddressType::class,$address);
        $form->handleRequest($request);
        if($form->isSubmitted() &&  $form->isValid()){
            $this->entityManger->flush();
            return $this->redirectToRoute('account_address');
        }
        return $this->render('account/address_add.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    #[Route('/compte/supprimer-addresse/{id}', name: 'account_address_delete')]
    public function delete($id): Response
    {
        $address = $this->entityManger->getRepository(Address::class)->findOneById($id);
        if ($address && $address->getUser() == $this->getUser()){
            $this->entityManger->remove($address);
            $this->entityManger->flush();
        }
        return  $this->redirectToRoute('account_address');


    }
}
