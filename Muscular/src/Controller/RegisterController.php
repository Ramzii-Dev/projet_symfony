<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class RegisterController extends AbstractController
{
    private $encoder;
    private $entityManger;

    public function __construct(EntityManagerInterface $entityManger, UserPasswordEncoderInterface $encoder)
    {
        $this->entityManger = $entityManger;
        $this->encoder = $encoder;
    }

    #[Route('/inscription', name: 'register')]
    public function index(Request $request)
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $password = $this->encoder->encodePassword($user, $user->getpassword());
            $user->setPassword($password);
            $this->entityManger->persist($user);
            $this->entityManger->flush();
        }
        return $this->render('register/index.html.twig', [
            'form' => $form->createView()
        ]);

    }
}
