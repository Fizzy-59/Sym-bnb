<?php

namespace App\Form;

use App\Entity\Ad;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdType extends AbstractType
{


    /**
     * Permet d'avoir la configuration de base d'un champ.
     *
     * @param string $label
     * @param string $placeholder
     * @return array
     */
    private function getConfiguration(string $label, string $placeholder)
    {
        return
        [
            'label' => $label,
            'attr' =>
            [
                'placeholder' => $placeholder
            ]
        ];
    }
//    TODO text Area bug
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class,
                $this->getConfiguration("Titre", "Tapez un super titre pour votre annonce !"))

            ->add('introduction', TextType::class,
                $this->getConfiguration("Introduction", "Donnez une description globale de l'annonce"))

            ->add('content', TextareaType::class,
                $this->getConfiguration("Description détaillé", "Taper une bonne description"))

            ->add('rooms', IntegerType::class,
                $this->getConfiguration("Nombre de chambre", "Nombre de chambre disponibles"))

            ->add('price', MoneyType::class,
                $this->getConfiguration("Prix par nuit", "Indiquer le prix pour une nuit"))

            //->add('slug', TextType::class, $this->getConfiguration("Chaine URL", "Adresse web (automatique)"))

            ->add('coverImage', UrlType::class,
                $this->getConfiguration("URL de l'image principale", "Donnez l'adresse de l'image"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}