<?php

namespace App\Order\Continuation;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ContinueOrdersType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('windowStart', DateType::class, [
                'label' => 'Beginn altes Bestellzeitfenster',
                'help' => 'Beginn des alten Bestellzeitfensters. Es werden nur Bestellungen aus diesem Zeitfenster fortgeschrieben.',
                'widget' => 'single_text',
            ])
            ->add('windowEnd', DateType::class, [
                'label' => 'Ende altes Bestellzeitfenster',
                'help' => 'Ende des alten Bestellzeitfensters. Es werden nur Bestellungen aus diesem Zeitfenster fortgeschrieben.',
                'widget' => 'single_text',
            ])
            ->add('excludeGrades', CollectionType::class, [
                'entry_type' => TextType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'row_attr' => [
                    'class' => 'field-collection'
                ],
                'label' => 'Zu ignorierende Klassen',
                'help' => '(Optional.) Bestellungen von Schülerinnen und Schülern dieser Klasse werden nicht fortgeschrieben.'
            ]);
    }
}
