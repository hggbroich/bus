<?php

namespace App\Validator;

use App\Entity\Street as StreetEntity;
use App\Repository\CityRepositoryInterface;
use App\Repository\StreetRepositoryInterface;
use Override;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class StreetValidator extends ConstraintValidator {

    public const int RelatedStreetNamesCharacterThreshold = 5;
    public const int PercentThreshold = 65;
    public const int NumberOfRelatedStreetNames = 5;

    public function __construct(
        private readonly CityRepositoryInterface $cityRepository,
        private readonly StreetRepositoryInterface $streetRepository,
        private readonly PropertyAccessorInterface $propertyAccessor
    ) { }

    #[Override]
    public function validate(mixed $value, Constraint $constraint): void {
        if($value === null) {
            return;
        }

        if(!$constraint instanceof Street) {
            throw new UnexpectedTypeException($constraint, Street::class);
        }

        if(!is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $plz = $this->propertyAccessor->getValue($this->context->getObject(), $constraint->plzPropertyPath);

        if(empty($plz)) {
            return;
        }

        $city = $this->cityRepository->findByPlz($plz);

        if($city === null) {
            return;
        }

        $streets = array_map(
            fn(StreetEntity $street) => $street->getName(),
            $this->streetRepository->findAllByCity($city)
        );

        if(in_array($value, $streets, true)) {
            return;
        }

        // Find related street names
        $related = [ ];

        if(mb_strlen($value) > self::RelatedStreetNamesCharacterThreshold) {
            foreach ($streets as $street) {
                similar_text($value, $street, $percent);

                if ($percent > self::PercentThreshold) {
                    $related[$street] = (int)$percent;
                }
            }

            arsort($related, SORT_NUMERIC);
            $related = array_slice($related, 0, self::NumberOfRelatedStreetNames, true);
            $related = array_keys($related);
        }

        $this->context
            ->buildViolation($constraint->message)
            ->setParameter('{{ name }}', $value)
            ->setParameter('{{ related }}', implode(', ', $related))
            ->addViolation();
    }
}
