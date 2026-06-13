<?php

namespace App\Form\EventListener;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Override;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\FormEvents;

readonly class NormalizePhoneNumberListener implements EventSubscriberInterface {

    public function __construct(
        private PhoneNumberUtil $phoneUtil,
    ) {

    }

    public function preSubmit(PreSubmitEvent $event): void {
        try {
            $parsedNumber = $this->phoneUtil->parse($event->getData(), defaultRegion: 'DE');
            $event->setData(
                $this->phoneUtil->format($parsedNumber, numberFormat: PhoneNumberFormat::E164)
            );
        } catch (NumberParseException) {

        }
    }

    #[Override]
    public static function getSubscribedEvents(): array {
        return [
            FormEvents::PRE_SUBMIT => 'preSubmit',
        ];
    }
}
