<?php

namespace App\Form\Type;

use App\Form\EventListener\NormalizerIbanListener;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class IbanType extends TextType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->addEventSubscriber(new NormalizerIbanListener());
    }
}
