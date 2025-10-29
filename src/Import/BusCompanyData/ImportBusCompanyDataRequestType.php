<?php

namespace App\Import\BusCompanyData;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ImportBusCompanyDataRequestType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('csv', FileType::class, [
                'label' => 'CSV-Datei'
            ])
            ->add('delimiter', TextType::class, [
                'label' => 'Trennzeichen'
            ])
            ->add('studentIdHeader', TextType::class, [
                'label' => 'Schüler ID-Spaltenname'
            ])
            ->add('customerIdHeader', TextType::class, [
                'label' => 'Kundennummer-Spaltenname'
            ])
            ->add('ticketHeader', TextType::class, [
                'label' => 'Ticket-Spaltenname'
            ]);
    }
}
