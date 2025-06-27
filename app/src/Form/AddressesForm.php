<?php
namespace App\Form;

use App\Entity\Addresses;
use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class AddressesForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'L\'adresse ne peut pas être vide.'
                    ]),
                    new Assert\Length([
                        'max' => 255,
                        'maxMessage' => 'L\'adresse ne peut pas dépasser {{ limit }} caractères.'
                    ])
                ],
                'attr' => [
                    'placeholder' => 'Saisissez votre adresse complète',
                    'class' => 'form-control'
                ]
            ])
            ->add('zipcode', TextType::class, [
                'label' => 'Code postal',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le code postal ne peut pas être vide.'
                    ]),
                    new Assert\Length([
                        'max' => 10,
                        'maxMessage' => 'Le code postal ne peut pas dépasser {{ limit }} caractères.'
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^[0-9]{5}$/',
                        'message' => 'Le code postal doit contenir exactement 5 chiffres.'
                    ])
                ],
                'attr' => [
                    'placeholder' => 'Ex: 75001',
                    'class' => 'form-control',
                    'maxlength' => 10
                ]
            ])
            ->add('country', TextType::class, [
                'label' => 'Pays',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le pays ne peut pas être vide.'
                    ]),
                    new Assert\Length([
                        'max' => 255,
                        'maxMessage' => 'Le nom du pays ne peut pas dépasser {{ limit }} caractères.'
                    ])
                ],
                'attr' => [
                    'placeholder' => 'Ex: France',
                    'class' => 'form-control'
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'La ville ne peut pas être vide.'
                    ]),
                    new Assert\Length([
                        'max' => 255,
                        'maxMessage' => 'Le nom de la ville ne peut pas dépasser {{ limit }} caractères.'
                    ])
                ],
                'attr' => [
                    'placeholder' => 'Ex: Paris',
                    'class' => 'form-control'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider mon adresse',
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Addresses::class,
        ]);

        // Permettre de passer l'utilisateur courant au formulaire
        $resolver->setDefined(['current_user']);
        $resolver->setAllowedTypes('current_user', [Users::class, 'null']);
    }
}
