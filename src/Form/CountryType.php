<?php

namespace App\Form;

use App\Entity\Country;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CountryType extends EntityType {
    public function configureOptions(OptionsResolver $resolver): void {
        parent::configureOptions($resolver);

        $resolver->setDefault('label', 'label.country');
        $resolver->setDefault('class', Country::class);
        $resolver->setDefault('choice_label', 'name');
        $resolver->setDefault('multiple', false);
        $resolver->setDefault('expanded', true);
    }
}
