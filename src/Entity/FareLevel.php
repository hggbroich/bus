<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Stringable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[UniqueEntity(fields: ['city'])]
class FareLevel implements Stringable {

    use IdTrait;
    use UuidTrait;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    public string $name;

    #[ORM\Column(type: Types::STRING, unique: true)]
    #[Assert\NotBlank]
    public string $city;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    public string $level;

    public function __construct() {
        $this->uuid = Uuid::v4()->toString();
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): FareLevel {
        $this->name = $name;
        return $this;
    }

    public function getCity(): string {
        return $this->city;
    }

    public function setCity(string $city): FareLevel {
        $this->city = $city;
        return $this;
    }

    public function getLevel(): string {
        return $this->level;
    }

    public function setLevel(string $level): FareLevel {
        $this->level = $level;
        return $this;
    }

    public function __toString(): string {
        return sprintf('%s (%s)', $this->getName(), $this->getLevel());
    }
}
