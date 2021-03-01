<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function add(): Response
    {
        return $this->render('account/address_add.html.twig');
    }
}
