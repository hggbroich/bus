<?php

namespace App\Settings\Form;

use App\Settings\KeyValuePair;
use App\Settings\ValueDataType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KeyValuePairType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('key', TextType::class, [
                'label' => $options['key_label'],
            ])
            ->add('value', TextType::class, [
                'label' => $options['value_label'],
            ]);

        if($options['has_type_field']) {
            $builder
                ->add('type', EnumType::class, [
                    'label' => 'settings.key_value.type',
                    'class' => ValueDataType::class,
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefault('data_class', KeyValuePair::class);
        $resolver->setDefault('has_type_field', true);
        $resolver->setDefault('key_label', 'settings.key_value.key');
        $resolver->setDefault('value_label', 'settings.key_value.value');
    }
}
