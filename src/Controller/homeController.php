<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class homeController extends AbstractController
{
    /**
     * @return mixed
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('home.html.twig');
    }


}