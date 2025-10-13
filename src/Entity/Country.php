<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Stringable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[UniqueEntity(fields: ['isoCode'])]
class Country implements Stringable {

    use IdTrait;
    use UuidTrait;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    private string $name;

    #[ORM\Column(type: Types::STRING, length: 2, unique: true)]
    #[Assert\Country]
    private string $isoCode;

    public function __construct() {
        $this->uuid = Uuid::uuid4();
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): Country {
        $this->name = $name;
        return $this;
    }

    public function getIsoCode(): string {
        return $this->isoCode;
    }

    public function setIsoCode(string $isoCode): Country {
        $this->isoCode = $isoCode;
        return $this;
    }

    public function __toString(): string {
        return sprintf('%s (%s)', $this->getName(), $this->getIsoCode());
    }
}
