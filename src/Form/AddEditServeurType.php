<?php

namespace App\Form;

use App\Entity\Serveur;
use App\Entity\restaurant;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddEditServeurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('datenaissance', null, [
                'widget' => 'single_text'
            ])
            ->add('restaurant', EntityType::class, [
                'class' => restaurant::class,
'choice_label' => 'id',
            ])
            ->add('Send', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Serveur::class,
        ]);
    }
}
