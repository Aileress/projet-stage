<?php

namespace App\Form;

use App\Entity\UserBu;
use PharIo\Manifest\Email;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;

class UserBuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName',TextType::class,['label' => 'Prénom'])
            ->add('lastName',TextType::class,['label' => 'Nom'])
            ->add('birthday',BirthdayType::class)
            ->add('birthPlace',TextType::class,['label' => 'Lieu de naissance'])
            ->add('email',EmailType::class)
            ->add('creationDate',DateType::class)
            ->add('listeServices', ChoiceType::class,[
                "mapped" => false,
                'choices' => [
                    'WIFI' => 'WIFI',
                    'internet' => 'internet',
                    'compte' => 'compte',
                    'impression' => 'impression',
                ],
                'expanded' => true,
                'multiple' => true
            ])
            ->add('Valider',SubmitType::class, 
            [
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserBu::class,
        ]);
    }
}