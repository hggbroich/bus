<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DistanceGoogleMapsType extends NumberType {

    public function configureOptions(OptionsResolver $resolver): void {
        parent::configureOptions($resolver);

        $resolver->setDefault('student_id', null);
        $resolver->setDefault('school_field_id', null);
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void {
        parent::buildView($view, $form, $options);

        $view->vars['student_id'] = $options['student_id'];
        $view->vars['school_field_id'] = $options['school_field_id'];
    }

    public function getBlockPrefix(): string {
        return 'distance_maps';
    }
}
