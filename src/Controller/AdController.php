<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * Céeer une annonce
     *
     * @Route("/ads/new", name="ads_create")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager )
    {
        $ad = new Ad();
        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid())
        {
//          Prise en compte des nouvelles images pour les faires persister avant de faire persister l'annonce
            foreach ($ad->getImages() as $image)
            {
                $image->setAd($ad);
                $manager->persist($image);
            }

            $manager->persist($ad);
            $manager->flush();

            $this->addFlash
            (
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été enrengistré ! "
            );

            return $this->redirectToRoute('ads_show',
            [
                'slug' => $ad->getSlug()
            ]);
        }

        return $this->render('ad/new.html.twig',
        [
            'form' => $form->createView()
        ]);
    }

    /**
     * Affiche une annonce
     *
     * @Route("/ads/{slug}", name="ads_show")
     * @param Ad $ad
     * @return Response
     */
    public function show (Ad $ad)
    {

//        $ad = $repo->findOneBySlug($slug);

        return $this->render('ad/show.html.twig',
            [
                'ad' => $ad
            ]);
    }
}
