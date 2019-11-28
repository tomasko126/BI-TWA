<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\Employee;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Používateľské meno:',
                'attr' => ['class' => 'uk-input uk-form-width-large uk-form-small'],
                'label_attr' => ['class' => 'uk-form-label'],
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Heslo:',
                'attr' => ['class' => 'uk-input uk-form-width-large uk-form-small'],
                'label_attr' => ['class' => 'uk-form-label'],
            ])
            ->add('validTo', DateTimeType::class, [
                'label' => 'Platnosť:',
                'label_attr' => ['class' => 'uk-form-label'],
                'attr' => ['class' => 'uk-input uk-form-width-large uk-form-small'],
                'required' => false,
                'widget' => 'single_text',
                'invalid_message' => 'Platnosť musí byť vo formáte 01.01.2018 12:15',
                'format' => 'd.MM.y H:mm'
            ])
            ->add('employee', EntityType::class, [
                'label' => 'Zamestnanec:',
                'label_attr' => ['class' => 'uk-form-label'],
                'attr' => ['class' => 'uk-select uk-form-width-large uk-form-small'],
                'class' => Employee::class,
                'choice_label' => function($employee){
                    return $employee->getName() . ' - ' . $employee->getEmail();
                }
            ])->add('save', SubmitType::class, [
                'attr' => ['class' => 'uk-button uk-button-default uk-button-small'],
                'label' => 'Odoslať'
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Account::class,
            'attr' => [
                'class' => 'uk-form-stacked'
            ]
        ]);
    }
}
