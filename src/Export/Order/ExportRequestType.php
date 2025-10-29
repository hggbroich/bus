<?php

namespace App\Export\Order;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ExportRequestType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('csv', FileType::class, [
                'label' => 'CSV-Datei',
                'help' => 'CSV-Datei, die als Vorlage genutzt wird. Alle Spaltenüberschriften werden übernommen. Alle Zeilen werden gelöscht und mit den getätigten Bestellungen ersetzt.'
            ])
            ->add('delimiter', TextType::class, [
                'label' => 'Trennzeichen'
            ])
            ->add('startDate', DateType::class, [
                'label' => 'Start-Datum',
                'help' => 'Es werden nur Bestellungen berücksichtigt, die in diesem Zeitraum getätigt wurden.'
            ])
            ->add('endDate', DateType::class, [
                'label' => 'End-Datum',
                'help' => 'Es werden nur Bestellungen berücksichtigt, die in diesem Zeitraum getätigt wurden.'
            ])
            ->add('studentIdHeader', TextType::class, [
                'label' => 'Schüler ID-Spaltenname'
            ])
            ->add('customerIdHeader', TextType::class, [
                'label' => 'Kundennummer-Spaltenname'
            ])
            ->add('ticketHeader', TextType::class, [
                'label' => 'Ticket-Spaltenname'
            ])
            ->add('fareLevelHeader', TextType::class, [
                'label' => 'Preisstufe-Spaltenname'
            ])
            ->add('firstnameHeader', TextType::class, [
                'label' => 'Vorname-Spaltenname'
            ])
            ->add('lastnameHeader', TextType::class, [
                'label' => 'Nachname-Spaltenname'
            ])
            ->add('streetHeader', TextType::class, [
                'label' => 'Strasse-Spaltenname'
            ])
            ->add('houseNumberHeader', TextType::class, [
                'label' => 'Hausnummer-Spaltenname'
            ])
            ->add('houseNumberSuffixHeader', TextType::class, [
                'label' => 'Hausnummerzusatz-Spaltenname'
            ])
            ->add('plzHeader', TextType::class, [
                'label' => 'PLZ-Spaltenname'
            ])
            ->add('cityHeader', TextType::class, [
                'label' => 'Ort-Spaltenname'
            ])
            ->add('phoneNumberHeader', TextType::class, [
                'label' => 'Telefonnummer-Spaltenname'
            ])
            ->add('birthdayHeader', TextType::class, [
                'label' => 'Geburtstag-Spaltenname'
            ])
            ->add('genderHeader', TextType::class, [
                'label' => 'Geschlecht-Spaltenname'
            ])
            ->add('priceLevelHeader', TextType::class, [
                'label' => 'Preisstufe-Spaltenname'
            ])
            ->add('ibanHeader', TextType::class, [
                'label' => 'IBAN-Spaltenname'
            ])
            ->add('depositorFirstnameHeader', TextType::class, [
                'label' => 'Vorname-Spaltenname (Kontoinhaber)'
            ])
            ->add('depositorLastnameHeader', TextType::class, [
                'label' => 'Nachname-Spaltenname (Kontoinhaber)'
            ])
            ->add('depositorStreetHeader', TextType::class, [
                'label' => 'Strasse-Spaltenname (Kontoinhaber)'
            ])
            ->add('depositorHouseNumberHeader', TextType::class, [
                'label' => 'Hausnummer-Spaltenname (Kontoinhaber)'
            ])
            ->add('depositorHouseNumberSuffixHeader', TextType::class, [
                'label' => 'Hausnummerzusatz-Spaltenname (Kontoinhaber)'
            ])
            ->add('depositorPLZHeader', TextType::class, [
                'label' => 'PLZ-Spaltenname (Kontoinhaber)'
            ])
            ->add('depositorCityHeader', TextType::class, [
                'label' => 'Ort-Spaltenname (Kontoinhaber)'
            ])
            ->add('depositorCountryHeader', TextType::class, [
                'label' => 'Land-Spaltenname (Kontoinhaber)'
            ])
            ->add('depositorBirthdayHeader', TextType::class, [
                'label' => 'Geburtstag-Spaltenname (Kontoinhaber)'
            ]);

    }
}
