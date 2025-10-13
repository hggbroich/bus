<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\NumberType;

class DistanceType extends NumberType {
    public function getBlockPrefix(): string {
        return 'distance';
    }
}
