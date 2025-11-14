<?php

namespace App\Import\StudentData;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ImportRequestType extends AbstractType {
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
            ->add('publicSchoolHeader', TextType::class, [
                'label' => 'Öffentliche Schule-Spaltenname',
                'help' => 'Die Schulnummer der öffentlichen Schule'
            ])
            ->add('distancePublicSchoolHeader', TextType::class, [
                'label' => 'Distanz öffentliche Schule-Spaltenname'
            ])
            ->add('distanceSchoolHeader', TextType::class, [
                'label' => 'Distanz Schule-Spaltenname'
            ]);
    }
}
