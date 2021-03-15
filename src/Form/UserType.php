<?php

namespace App\Form;

use   App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo')
            ->add('prenom')
            ->add('nom')
            ->add('telephone')
            ->add('mail', null,  ['label'=>'Email'])
           // ->add('actif')

            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'required' => true,
                'invalid_message' => 'Les mots de passe ne correspondent pas.',
                'constraints' => array(
                    new NotBlank(),
                    new Length(array('min' => 6)),
                ),
                'first_options'  => array('label' => 'Mot de passe '),
                'second_options' => array('label' => 'Confirmation '),
            ))
            ->add('site',null, ['choice_label' => 'nom'])

            // Ajout de photo
            ->add('photo', FileType::class,[
                'label' => 'Ma photo : ',
                'mapped' => true,
                'required' => false
            ])

//            ->add('brochure', FileType::class, [
//                // unmapped fields can't define their validation using annotations
//                // in the associated entity, so you can use the PHP constraint classes
//                'constraints' => [
//                    new File([
//                        'maxSize' => '1024k',
//                        'mimeTypes' => [
//                            'application/pdf',
//                            'application/x-pdf',
//                        ],
//                        'mimeTypesMessage' => 'Please upload a valid PDF document',
//                    ])
//                ],
//            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
