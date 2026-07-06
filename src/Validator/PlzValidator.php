<?php

namespace App\Validator;

use App\Entity\Country;
use App\Repository\CityRepositoryInterface;
use Override;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class PlzValidator extends ConstraintValidator {

    public function __construct(
        private readonly CityRepositoryInterface $cityRepository,
        private readonly PropertyAccessorInterface $propertyAccessor
    ) {

    }

    #[Override]
    public function validate(mixed $value, Constraint $constraint): void {
        if($value === null) {
            return;
        }

        if (!$constraint instanceof Plz) {
            throw new UnexpectedTypeException($constraint, Plz::class);
        }

        if (!is_int($value)) {
            throw new UnexpectedValueException($value, 'int');
        }

        $country = $this->propertyAccessor->getValue($this->context->getObject(), $constraint->countryPropertyName);

        if (!$country instanceof Country || $country->getIsoCode() !== 'DE') {
            return;
        }

        $city = $this->cityRepository->findByPlz($value);

        if($city === null) {
            $this->context
                ->buildViolation($constraint->message)
                ->setParameter('{{ code }}', $value)
                ->addViolation();
        }
    }
}
