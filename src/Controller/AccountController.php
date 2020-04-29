<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    /**
     * Permet d'afficher et de gérer le formulaire de contact
     *
     * @Route("/login", name="account_login")
     */
    public function login()
    {
        return $this->render('account/login.html.twig');
    }

    /**
     *Permet de se déconnecter
     *
     * @Route("/logout", name="account_logout")
     */
    public function logout()
    {
        // Rien
    }
}
