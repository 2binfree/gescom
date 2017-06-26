<?php

namespace GescomBundle\Form;

use GescomBundle\Entity\SupplierFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SupplierFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du fournisseur',
                'required' => false,
            ])
            ->add('postalCode', TextType::class, [
                'label' => 'Code postal',
                'required' => false,
            ])
            ->add('town', TextType::class, [
                'label' => 'Ville',
                'required' => false,
            ])
            ->add('deliveryTime', TextType::class, [
                'label' => 'Délai de livraison',
                'required' => false,
            ])
            ->add('score', TextType::class, [
                'label' => 'Score',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SupplierFilter::class,
            'validation_groups' => false,
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'gescom_bundle_filter_type';
    }
}
