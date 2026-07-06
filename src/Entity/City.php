<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Ramsey\Uuid\Uuid;
use Stringable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity]
#[ORM\UniqueConstraint(fields: ['plz', 'name'])]
#[UniqueEntity(fields: ['plz', 'name'])]
class City implements Stringable {
    use IdTrait;
    use UuidTrait;

    #[ORM\Column(type: Types::STRING, length: 5)]
    private string $plz;

    #[ORM\Column(type: Types::STRING)]
    private string $name;

    #[Column(type: Types::STRING)]
    private string $federalState;

    public function __construct() {
        $this->uuid = Uuid::uuid4();
    }

    public function getPlz(): string {
        return $this->plz;
    }

    public function setPlz(string $plz): City {
        $this->plz = $plz;
        return $this;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): City {
        $this->name = $name;
        return $this;
    }

    public function getFederalState(): string {
        return $this->federalState;
    }

    public function setFederalState(string $federalState): City {
        $this->federalState = $federalState;
        return $this;
    }


    public function __toString(): string {
        return sprintf('%s %s', $this->plz, $this->name);
    }
}
