<?php

namespace App\Controller;

use App\Entity\PasswordUpdate;
use App\Entity\User;
use App\Form\AccountType;
use App\Form\PasswordUpdateType;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    /**
     * Login
     * Permet d'afficher et de gérer le formulaire de contact
     *
     * @Route("/login", name="account_login")
     *
     * @param  AuthenticationUtils $utils
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('account/login.html.twig',
        [
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }

    /**
     * Logout
     *Permet de se déconnecter
     *
     * @Route("/logout", name="account_logout")
     */
    public function logout()
    {
        // Rien
    }

    /**
     * Register
     * Permet d'afficher le formulaire d'inscription
     *
     * @Route("/register", name="account_register")
     *
     * @param  Request $request
     * @param  EntityManagerInterface $manager
     * @param  UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function register(Request $request,  EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre compte a bien été créé."
            );

            return $this->redirectToRoute('account_login');
        }

        return $this->render('account/registration.html.twig',
        [
            'form' => $form->createView()
        ]);
    }


    /**
     * Access profil
     * Permet d'afficher et de traiter le formulaire de modification de profil
     *
     * @Route("/account/profile", name="account_profile")
     *
     * @IsGranted("ROLE_USER")
     * @param  Request $request
     * @param  EntityManagerInterface $manager
     * @return Response
     */
    public function profile(Request $request, EntityManagerInterface $manager)
    {
        $user = $this->getUser();
        $form = $this->createForm(AccountType::class,  $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre compte a bien été modifié."
            );
        }

        return $this->render('account/profile.html.twig',
        [
            'form' => $form->createView()
        ]);
    }

    /**
     * Modify Password
     * Permet de modifier le mot de passe
     *
     * @Route("/account/password-update", name="account_password")
     *
     * @IsGranted("ROLE_USER")
     * @param  Request $request
     * @param  EntityManagerInterface $manager
     * @param  UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function updatePassword(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $passwordUpdate = new PasswordUpdate();
        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

        $user = $this->getUser();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $newPassword = $passwordUpdate->getNewPassword();
            $hash = $encoder->encodePassword($user, $newPassword);
            $user->setHash($hash);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre mot de passe a bien été modifié."
            );

            return $this->redirectToRoute('home');
        }

        return $this->render('account/password.html.twig',
        [
            'form' => $form->createView()
        ]);
    }

    /**
     * Account
     * Permet d'afficher le profil de l'utilsateur connecté
     *
     * @Route("/account", name="account_index")
     *
     * @IsGranted("ROLE_USER")
     */
    public function myAccount()
    {
        $user = $this->getUser();

        return $this->render('user/index.html.twig',
        [
            'user' => $user
        ]);
    }
}
