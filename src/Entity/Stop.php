<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Stringable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[UniqueEntity(fields: ['externalId'])]
class Stop implements Stringable {
    use IdTrait;
    use UuidTrait;

    #[ORM\Column(type: Types::STRING, length: 255, unique: true, nullable: true)]
    #[Assert\NotBlank(allowNull: true)]
    private ?string $externalId = null;

    #[ORM\Column(type: Types::STRING, length: 255, unique: true)]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(type: Types::FLOAT)]
    private float $latitude = 0;

    #[ORM\Column(type: Types::FLOAT)]
    private float $longitude = 0;

    public function __construct() {
        $this->uuid = Uuid::uuid4();
    }

    public function getExternalId(): ?string {
        return $this->externalId;
    }

    public function setExternalId(?string $externalId): Stop {
        $this->externalId = $externalId;
        return $this;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(?string $name): Stop {
        $this->name = $name;
        return $this;
    }

    public function getLatitude(): float {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): Stop {
        $this->latitude = $latitude;
        return $this;
    }

    public function getLongitude(): float {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): Stop {
        $this->longitude = $longitude;
        return $this;
    }

    public function __toString() {
        return $this->getName();
    }
}
