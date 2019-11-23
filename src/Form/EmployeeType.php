<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\Employee;
use App\Entity\Role;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class EmployeeType extends AbstractType
{
    /** @var AuthorizationCheckerInterface  */
    private $auth;

    public function __construct(AuthorizationCheckerInterface $auth) {
        $this->auth = $auth;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => 'uk-input uk-form-width-large uk-form-small'],
                'label_attr' => ['class' => 'uk-form-label'],
                'label' => 'Meno:'
            ])
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'uk-input uk-form-width-large uk-form-small'],
                'label_attr' => ['class' => 'uk-form-label'],
                'label' => 'Email: '
            ])
            ->add('roles', EntityType::class, [
                'label' => 'Funkcie: ',
                'class' => Role::class,
                'multiple' => true,
                'label_attr' => ['class' => 'uk-form-label'],
                'attr' => ['class' => 'uk-select uk-form-width-large uk-form-small'],
                'query_builder' => function (EntityRepository $er) {
                    if ($this->auth->isGranted('ROLE_ADMIN')) {
                        return $er->createQueryBuilder('role');
                    } else {
                        return $er->createQueryBuilder('role')->where('role.isVisible = true');
                    }
                },
            ])
            ->add('phone', TelType::class, [
                'attr' => ['class' => 'uk-input uk-form-width-large uk-form-small'],
                'label_attr' => ['class' => 'uk-form-label'],
                'label' => 'Telefónne číslo:'
            ])
            ->add('web', UrlType::class, [
                'attr' => ['class' => 'uk-input uk-form-width-large uk-form-small'],
                'label_attr' => ['class' => 'uk-form-label'],
                'label' => 'Webová stránka: '
            ])
            ->add('room', TextType::class, [
                'attr' => ['class' => 'uk-input uk-form-width-large uk-form-small'],
                'label_attr' => ['class' => 'uk-form-label'],
                'label' => 'Miestnosť:'
            ])->add('save', SubmitType::class, [
                'attr' => ['class' => 'uk-button uk-button-default uk-button-small'],
                'label' => 'Odoslať'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Employee::class,
            'attr' => [
                'class' => 'uk-form-stacked'
            ]
        ]);
    }
}
