<?php

namespace App\Form;

use App\Entity\Sortie;

use Doctrine\ORM\EntityRepository;
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
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',null,['label'=>'Nom de la sortie'])
            ->add('dateHeureDebut',DateTimeType::class, [
                // Il faut diviser la date et l'heure pour pouvoir les modifier
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
            ->add('supprimer', SubmitType::class, ['label' => 'Supprimer la sortie', 'row_attr' => ['class' => 'alert button']])
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
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
