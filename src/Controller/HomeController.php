<?php


namespace App\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/hello/{prenom}/{age}", name="helloPrenom")
     * @Route("/hello", name="helloBase")
     */

    public function hello($prenom = "anonyme", $age = 'nul')
    {

        return $this->render
        (
            'hello.html.twig', ['prenom' => $prenom, 'age' => $age]
        );
    }


    /**
     * @Route("/", name="homepage")
     */

    public function home()
    {
        $prenoms = ["Romain" => 26, "Gaetan" => 27, "Julien" => 30];

        return $this->render
        (
          'home/home.html.twig',
            [
                'title' => 'Bonjour à tous',
                'age' => 31,
                'tableau' => $prenoms
            ]
        );
    }
}