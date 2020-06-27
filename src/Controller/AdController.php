<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     *
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
     *
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager )
    {
        $user = $this->getUser();
        $ad = new Ad();
        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid())
        {
//          Prise en compte des nouvelles images pour les faire persister avant de faire persister l'annonce
            foreach ($ad->getImages() as $image)
            {
                $image->setAd($ad);
                $manager->persist($image);
            }
            
            $ad->setAuthor($user);

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
     * Permet d'afficher le formulaire d'édition
     *
     * @Route("/ads/{slug}/edit", name="ads_edit")
     *
     * @Security("is_granted('ROLE_USER') and user === ad.getAuthor()", message="Cette annonce ne vous appartient pas")
     * @param  Ad $ad
     * @param  Request $request
     * @param  EntityManagerInterface $manager
     * @return Response
     */
    public function edit(Ad $ad, Request $request, EntityManagerInterface $manager)
    {

        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
//          Prise en compte des nouvelles images pour les faire persister avant de faire persister l'annonce
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
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été modifié ! "
            );

            return $this->redirectToRoute('ads_show',
                [
                    'slug' => $ad->getSlug()
                ]);
        }

        return $this->render('ad/edit.html.twig',
        [
            'form' => $form->createView(),
            'ad' => $ad
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

    /**
     * Delete
     * Permet de supprimer une annonce
     *
     * @Route("/ads/{slug}/delete", name="ads_delete")
     *
     * @Security("is_granted('ROLE_USER') and user == ad.getAuthor()", message="Vous n'avez pas le droit d'accèder à cette ressource")
     * @param Ad $ad
     * @param EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Ad $ad, EntityManagerInterface $manager)
    {
        $manager->remove($ad);
        $manager->flush();

        $this->addFlash(
            'succes',
            "L'annonce <strong>{$ad->getTitle()}</strong> a bien été supprimée !"
        );

        return $this->redirectToRoute("ads_index");
    }
}
