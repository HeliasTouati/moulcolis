<?php

namespace App\Form;

use App\Entity\Orders;
use App\Entity\Packages;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Regex;

class PackagesForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('poids_kg', NumberType::class, [
                'label' => 'Poids (kg)',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ex: 2.5',
                    'step' => '0.1',
                    'min' => '0.1'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le poids est obligatoire'
                    ]),
                    new Positive([
                        'message' => 'Le poids doit être positif'
                    ])
                ],
                'help' => 'Poids en kilogrammes (minimum 0.1 kg)',
                'scale' => 2, // 2 décimales
                'html5' => true
            ])
            ->add('dimensions_cm', TextType::class, [
                'label' => 'Dimensions (cm)',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ex: 30x20x15',
                    'pattern' => '[0-9]+x[0-9]+x[0-9]+',
                    'title' => 'Format: LongueurxLargeurxHauteur (ex: 30x20x15)'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Les dimensions sont obligatoires'
                    ]),
                    new Regex([
                        'pattern' => '/^\d+x\d+x\d+$/',
                        'message' => 'Format invalide. Utilisez le format: LongueurxLargeurxHauteur (ex: 30x20x15)'
                    ])
                ],
                'help' => 'Format: Longueur x Largeur x Hauteur en cm (ex: 30x20x15)'
            ])
            ->add('reference', TextType::class, [
                'label' => 'Référence du colis',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ex: COL-2024-001',
                    'maxlength' => 50
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'La référence est obligatoire'
                    ]),
                    new Length([
                        'min' => 3,
                        'max' => 50,
                        'minMessage' => 'La référence doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'La référence ne peut pas dépasser {{ limit }} caractères'
                    ])
                ],
                'help' => 'Référence unique pour identifier votre colis (3-50 caractères)'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Payer ma commande',
                'attr' => [
                    'class' => 'btn btn-primary btn-lg mt-3'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Packages::class,
        ]);
    }
}
