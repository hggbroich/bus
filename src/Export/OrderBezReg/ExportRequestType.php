<?php

namespace App\Export\OrderBezReg;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ExportRequestType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('delimiter', TextType::class, [
                'label' => 'Trennzeichen'
            ])
            ->add('status', TextType::class, [
                'label' => 'Status',
                'help' => 'Nur Schülerinnen und Schüler mit diesem Status werden beim Export berücksichtigt. Keine Mehrfachauswahl möglich.'
            ])
            ->add('startDate', DateType::class, [
                'label' => 'Start-Datum',
                'help' => 'Es werden nur Bestellungen berücksichtigt, die in diesem Zeitraum getätigt wurden.'
            ])
            ->add('endDate', DateType::class, [
                'label' => 'End-Datum',
                'help' => 'Es werden nur Bestellungen berücksichtigt, die in diesem Zeitraum getätigt wurden.'
            ]);
    }
}
