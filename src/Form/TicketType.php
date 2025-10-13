<?php

namespace App\Form;

use App\Entity\Ticket;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends EntityType {
    public function configureOptions(OptionsResolver $resolver): void {
        parent::configureOptions($resolver);

        $resolver->setDefault('label', 'label.ticket');
        $resolver->setDefault('class', Ticket::class);
        $resolver->setDefault('multiple', false);
        $resolver->setDefault('expanded', true);
    }
}
