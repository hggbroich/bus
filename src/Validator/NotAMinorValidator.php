<?php

namespace App\Validator;

use App\Settings\OrderSettings;
use DateTimeInterface;
use Override;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Contracts\Translation\TranslatorInterface;

class NotAMinorValidator extends ConstraintValidator {

    public function __construct(
        private readonly OrderSettings $orderSettings,
        private readonly TranslatorInterface $translator
    ) {

    }

    #[Override]
    public function validate(mixed $value, Constraint $constraint): void {
        if(!$value instanceof DateTimeInterface) {
            throw new UnexpectedValueException($value, DateTimeInterface::class);
        }

        if(!$constraint instanceof NotAMinor) {
            throw new UnexpectedValueException($constraint, NotAMinor::class);
        }

        $diff = $value->diff($this->orderSettings->effectiveDate);

        if(abs($diff->y) < $this->orderSettings->legalAge) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ date }}', $this->orderSettings->effectiveDate->format($this->translator->trans('date.format')))
                ->addViolation();
        }
    }
}
