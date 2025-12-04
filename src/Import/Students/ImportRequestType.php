<?php

namespace App\Import\Students;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
            ->add('idHeader', TextType::class, [
                'label' => 'ID-Spaltenname'
            ])
            ->add('statusHeader', TextType::class, [
                'label' => 'Status-Spaltenname'
            ])
            ->add('firstnameHeader', TextType::class, [
                'label' => 'Vorname-Spaltenname'
            ])
            ->add('lastnameHeader', TextType::class, [
                'label' => 'Nachname-Spaltenname'
            ])
            ->add('emailHeader', TextType::class, [
                'label' => 'E-Mail-Spaltenname'
            ])
            ->add('gradeHeader', TextType::class, [
                'label' => 'Klassen-Spaltenname'
            ])
            ->add('birthdayHeader', TextType::class, [
                'label' => 'Geburtsdatum-Spaltenname'
            ])
            ->add('streetHeader', TextType::class, [
                'label' => 'Strasse-Spaltenname'
            ])
            ->add('houseNumberHeader', TextType::class, [
                'label' => 'Hausnummer-Spaltenname'
            ])
            ->add('cityHeader', TextType::class, [
                'label' => 'Ort-Spaltenname'
            ])
            ->add('plzHeader', TextType::class, [
                'label' => 'PLZ-Spaltenname'
            ])
            ->add('entranceDateHeader', TextType::class, [
                'label' => 'Aufnahmedatum-Spaltenname'
            ])
            ->add('leaveDateHeader', TextType::class, [
                'label' => 'Entlassdatum-Spaltenname'
            ])
            ->add('genderHeader', TextType::class, [
                'label' => 'Geschlecht-Spaltenname'
            ])
            ->add('maleGenderValue', TextType::class, [
                'label' => 'Wert für männlich'
            ])
            ->add('femaleGenderValue', TextType::class, [
                'label' => 'Wert für weiblich'
            ])
            ->add('diversGenderValue', TextType::class, [
                'label' => 'Wert für divers'
            ])
            ->add('remove', CheckboxType::class, [
                'label' => 'Nicht in der CSV-Datei aufgeführte Schülerinnen und Schüler löschen',
                'required' => false
            ]);
    }
}
