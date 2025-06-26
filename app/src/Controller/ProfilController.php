<?php

namespace App\Controller;

use App\Entity\Addresses;
use App\Form\AddressesForm;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(): Response
    {
        return $this->render('profil/index.html.twig', [
            'controller_name' => 'ProfilController',
        ]);
    }

    public function adresse(Request $request, ): Response
    {
        $adresse = new Addresses();
        $form = $this->createForm(AddressesForm::class, $adresse);
        $form->handleRequest($request);


        return $this->render('profil/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
