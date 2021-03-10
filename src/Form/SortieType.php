<?php

namespace App\Form;

use App\Entity\Sortie;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',null,['label'=>'Nom de la sortie'])
            ->add('dateHeureDebut',null, ['label'=>'Date et heure de la sortie'])
            ->add('dateLimiteInscription',null, ['label'=>'Date limite d\'inscription'])
            ->add('nbrePlacesMax')
            ->add('duree')
            ->add('description', null, ['label'=>'Description et infos'])
            ->add('villes',null,['choice_label'=>'nom', 'label'=>'Ville'])

            ->add('lieu',null,['choice_label'=>'nom'])

//            ->add('villes', null, array(
//                'class'    => 'Bundle:Ville',
//                'property' => 'nom',
//                'empty_value' => '-- sélectionner un parc --',
//                'label'    => 'Choisir le parc immobilier : ',
//            ))
//            ->add('lieu', null, array(
//                'class'    => 'Bundle:Lieu',
//                'query_builder' => function(EntityRepository $er) {
//                    return $er->createQueryBuilder('l')
//                        ->join('l.ville','v');
//                },
//                'property' => 'nom',
//                'empty_value' => '-- sélectionner un ensemble --',
//                'label'    => 'Choisir l\'ensemble : ',
//            ))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
