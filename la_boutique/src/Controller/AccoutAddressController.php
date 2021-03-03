<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccoutAddressController extends AbstractController
{
    #[Route('/compte/addresse', name: 'account_address')]
    public function index(): Response
    {
        return $this->render('account/address.html.twig');
    }

    #[Route('/compte/ajouter-addresse', name: 'account_address_add')]
    public function add(Request $request): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class,$address);
        $form->handleRequest($request);
        if($form->isSubmitted() &&  $form->isValid()){
            $address->setUser($this->getUser());
            dd($address);
        }
        return $this->render('account/address_add.html.twig',[
            'form'=>$form->createView()
        ]);
    }
}
