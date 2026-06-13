<?php

namespace App\Form\EventListener;

use Override;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\FormEvents;

readonly class NormalizerIbanListener implements EventSubscriberInterface {

    public function preSubmit(PreSubmitEvent $event): void {
        $value = $event->getData();

        if(!is_string($value)) {
            return;
        }

        $value = preg_replace('/\s+/', '', $value);
        $value = mb_strtoupper($value);

        $event->setData($value);
    }

    #[Override]
    public static function getSubscribedEvents(): array {
        return [
            FormEvents::PRE_SUBMIT => 'preSubmit',
        ];
    }
}
