<?php

namespace App\Form;

use App\Entity\Sortie;
use App\Entity\Lieu;
use App\Entity\Ville;
use App\Repository\LieuRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public $lieuRepository;

    public function __construct(LieuRepository $lieuRepository)
    {
        $this->lieuRepository = $lieuRepository;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        //$em=$this->getDoctrine()->getManager();

        $builder
            ->add('nom',null,['label'=>'Nom de la sortie'])
            ->add('dateHeureDebut',DateTimeType::class, [
                // Il faut diviser la date et l'heure pour pouvoir les modifier
//                'view_timezone' => 'Europe/Paris',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',

            ])
            ->add('dateLimiteInscription',DateType::class, [
                'widget' => 'single_text',
                // this is actually the default format for single_text
                'format' => 'yyyy-MM-dd'
            ])
            ->add('nbrePlacesMax')
            ->add('duree')
            ->add('description', null, ['label'=>'Description et infos'])
            ->add('villes',null,['choice_label'=>'nom', 'label'=>'Ville'])
            ->add('modifAnnul',null,['label'=>'Motif: '])
            ->add('lieu',null,['choice_label'=>'nom'])
            ->add('enregistrer', SubmitType::class, ['label' => 'Enregistrer', 'row_attr' => ['class' => 'success button']])
            ->add('publier', SubmitType::class, ['label' => 'Publier la sortie', 'row_attr' => ['class' => 'button']])
            ->add('annuler', SubmitType::class, ['label' => 'Annuler', 'row_attr' => ['class' => 'alert button']])
            ->add('supprimer', SubmitType::class, ['label' => 'Supprimer la sortie', 'row_attr' => ['class' => 'alert button']]);
//            ->add('villes',EntityType::class,[
//                'class'=>Ville::class,
//                'placeholder'=>'Choisissez une ville',
////                'mapped'=>false,
//                'required'=>false
//            ]);

//        $builder->get('villes')->addEventListener(
//            FormEvents::POST_SUBMIT,
//            function(FormEvent $event){
//
//
//
//                $form=$event->getForm();
//
//
//                $i=0;
//                $nomLieux=[];
//
//                $lieux=$this->lieuRepository->findAll();
//
//
//
//                foreach ($lieux as $lieu){
//
//
//                    if($lieu->getVille()->getId()==$event->getData()){
//                        $i++;
//                        $lieuxVille[$i]=$lieu;
//
//
//                    }
//
//                }
//
//
//                $builder = $form->getParent()->getConfig()->getFormFactory()->createNamedBuilder(
//                    'lieu',
//                    EntityType::class,
//                    null,
//                    [ //Création d'un sous-formulaire
//                        'class'=>Lieu::class,
//                        'placeholder'=>'Choisissez un lieu',
////                    'mapped'=>false,
//                        'required'=>false,
//                        'auto_initialize'=>false,
//                        'choices'=> $lieuxVille
//                    ]
//                );
//
//
//                /*$builder->addEventListener(
//                    FormEvents::POST_SUBMIT,
//                    function (FormEvent $event){
//                        dump($event->getForm());
//                    }
//                );*/
//
//                $form->getParent()->add($builder->getForm());


//                $ville=$event->getData();
//                $form=$event->getForm();
//
//                $em=$this->getDoctrine()->getManager();
//
//                $lieuRepository=new LieuRepository();
//
//                if (!$ville || null === $ville->getId()){
//                    $lieuByVille=$lieuRepository->findAll();
//                    $form->add('lieu',EntityType::class,[
//                        'class'=>Lieu::class,
//                        'placeholder'=>'Choisissez un lieu',
//                        'choices'=> $lieuByVille
//                    ]);
//                }
//
//
//
//        }
//        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
