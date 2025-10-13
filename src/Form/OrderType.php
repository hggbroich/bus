<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class OrderType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('country', CountryType::class)
            ->add('phoneNumber', TextType::class, [
                'label' => 'label.phone',
            ])
            ->add('email', EmailType::class, [
                'label' => 'label.email',
            ])
            ->add('iban', TextType::class, [
                'label' => 'label.iban',
            ])
            ->add('bic', TextType::class, [
                'label' => 'label.bic',
            ])
            //->add('ticket', TicketType::class)
            ->add('siblings', CollectionType::class, [
                'entry_type' => StudentSiblingType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ]);
    }
}
