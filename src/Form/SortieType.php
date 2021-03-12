<?php

namespace App\Form;

use App\Entity\Sortie;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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
            //->add('villes',null,['choice_label'=>'nom', 'label'=>'Ville'])

            ->add('lieu',null,['choice_label'=>'nom'])
            ->add('enregistrer', SubmitType::class, ['label' => 'Enregistrer'])
            ->add('publier', SubmitType::class, ['label' => 'Publier la sortie'])
            ->add('annuler', SubmitType::class, ['label' => 'Annuler'])
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $ville = $event->getData();
                $form = $event->getForm();

                // checks if the Product object is "new"
                // If no data is passed to the form, the data is "null".
                // This should be considered a new "Product"
                if (!$ville || null === $ville->getNom()) {
                    $form->add('villes',null,['choice_label'=>'nom', 'label'=>'Ville']);
                }
            });








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
