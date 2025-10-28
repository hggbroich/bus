<?php

namespace App\Form;

use App\Entity\PaymentInterval;
use App\Entity\TicketPaymentInterval;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketPaymentIntervallType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('paymentInterval', EntityType::class, [
                'label' => 'label.payment_interval',
                'class' => PaymentInterval::class
            ])
            ->add('externalId', TextType::class, [
                'label' => 'label.external_id.label',
                'help' => 'label.external_id.help',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefault('data_class', TicketPaymentInterval::class);
    }
}
