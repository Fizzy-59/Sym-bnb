<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Repository\AdRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\SubmitButton;
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
     * @return Response
     */
    public function create()
    {
        $ad = new Ad();
        $form = $this->createFormBuilder($ad)
            ->add('title')
            ->add('introduction')
            ->add('content')
            ->add('rooms')
            ->add('price')
            ->add('coverImage')
            ->add('save', SubmitType::class,
                [
                    'label' => 'Créer la nouvelle annonce',
                    'attr' => ['btn bnt-primary']
                ])
            ->getForm();

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
