<?php

namespace App\Form\Type;

use App\Form\EventListener\NormalizePhoneNumberListener;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class PhoneNumberType extends TextType {
    public function __construct(
        private readonly NormalizePhoneNumberListener $normalizePhoneNumberListener
    ) {

    }

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->addEventSubscriber($this->normalizePhoneNumberListener);
    }
}
