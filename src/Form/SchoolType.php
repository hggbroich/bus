<?php

namespace App\Form;

use App\Entity\Country;
use App\Entity\School;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SchoolType extends EntityType {
    public function configureOptions(OptionsResolver $resolver): void {
        parent::configureOptions($resolver);

        $resolver->setDefault('label', 'label.school');
        $resolver->setDefault('class', School::class);
        //$resolver->setDefault('choice_label', 'name');
        $resolver->setDefault('multiple', false);
        $resolver->setDefault('expanded', false);
    }
}
