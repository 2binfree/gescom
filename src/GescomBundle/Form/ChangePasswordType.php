<?php

namespace GescomBundle\Form;

use GescomBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password', PasswordType::class, ['label' => 'Entrez votre nouveau mot passe'])
            ->add('passwordCompare', PasswordType::class, [
                'label' => 'Entrez à nouveau le mot de passe',
                'mapped' => false
            ])
            ->add('submit', SubmitType::class, ['label' => 'Réinitialiser']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class
        ));
    }

    public function getBlockPrefix()
    {
        return 'gescom_bundle_user_type';
    }
}
