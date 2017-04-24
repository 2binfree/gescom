<?php

namespace GescomBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SupplierListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // add a select for supplier name to integrate into product
        // The selector is built through the provider entity in which the vendor name is stored
        $builder
            ->add('name', EntityType::class, [
                'class'         => 'GescomBundle\Entity\Supplier',
                'choice_label'  => 'name',
                //  We want to be able to select several suppliers
                'multiple'      => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null,
        ));
    }

    public function getBlockPrefix()
    {
        return 'gescom_bundle_supplier_type';
    }
}
