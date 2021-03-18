<?php

namespace App\Form;



use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchIndexType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('q', TextType::class, [
                'label' => "rechercher",
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher'
                ]
            ])
            ->add('dateMin', DateType::class,[
                'label' =>"Entre",
                'widget' => 'single_text',
                'input'  => 'datetime_immutable'
                ])

            ->add('dateMax', DateType::class,[
                'label' =>"Entre",
                'widget' => 'single_text',
                'input'  => 'datetime_immutable'
            ])

            ->add('orga', CheckboxType::class,[
                'label'    => 'Sorties dont je suis l organisateur/trice',
                'required' => false,
            ])

            ->add('inscrit', CheckboxType::class,[
                'label'    => 'Sorties auxquelles je suis inscrit/e',
                'required' => false,
            ])
            ->add('pasInscrit', CheckboxType::class,[
                'label'    => 'Sorties auxquelles je ne suis pas inscrit/e',
                'required' => false,
            ])
            ->add('passee', CheckboxType::class,[
                'label'    => 'Sorties passées',
                'required' => false,
            ])

//            ->add('categories', EntityType::class, [
//                'label' => false,
//                'required' => false,
//                'class' => Category::class,
        //pour avoir une liste de chekbox, il faut les 2 attributs suivants: ( voir la doc entityType)
//                'expanded' => true,
//                'multiple' => true
//            ])
//            ->add('min', NumberType::class, [
//                'label' => false,
//                'required' => false,
//                'attr' => [
//                    'placeholder' => 'Prix min'
//                ]
//            ])
//            ->add('max', NumberType::class, [
//                'label' => false,
//                'required' => false,
//                'attr' => [
//                    'placeholder' => 'Prix max'
//                ]
//            ])
//            ->add('promo', CheckboxType::class, [
//                'label' => 'En promotion',
//                'required' => false,
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => \RechercheDonnees::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    // Pour avoir une url propre
    public function getBlockPrefix()
    {
        return '';
    }

}