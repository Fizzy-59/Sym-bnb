<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Repository\AdRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     * @param AdRepository $adRepository
     * @return Response
     */
    public function index(AdRepository $adRepository)
    {
        $ads = $adRepository->findAll();

        return $this->render('ad/index.html.twig',
        [
            'ads' => $ads,
        ]);
    }

    /**
     * Affiche une annonce
     *
     * @Route("/ads/{slug}", name="ads_show")
     *
     * @param $slug
     * @param AdRepository $adRepository
     * @return Response
     */
    public function show ($slug, AdRepository $repo)
    {

        $ad = $repo->findOneBySlug($slug);

        return $this->render('ad/show.html.twig',
            [
                'ad' => $ad
            ]);
    }
}
