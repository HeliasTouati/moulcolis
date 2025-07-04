<?php

namespace App\Form;

use App\Entity\Addresses;
use App\Entity\Orders;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrdersForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('addresses', EntityType::class, [
                'class' => Addresses::class,
                'choice_label' => function(Addresses $address) {
                    return $address->getAddress() . ' - ' . $address->getZipcode() . ' ' . $address->getCountry();
                },
            ])
            ->add('submit', SubmitType::class, [
                              'label' => 'Suivant',
                              'attr' => [
                                  'class' => 'btn btn-primary'
                              ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Orders::class,
        ]);
    }
}
