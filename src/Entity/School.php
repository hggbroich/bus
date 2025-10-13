<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Stringable;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class School implements Stringable {

    use IdTrait;
    use UuidTrait;

    #[ORM\Column(type: Types::INTEGER)]
    private int $schoolNumber;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Assert\NotBlank]
    private string $name;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    #[Assert\NotBlank(allowNull: true)]
    private ?string $address = null;

    #[ORM\Column(type: Types::INTEGER, length: 5, nullable: true)]
    private ?int $plz = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    #[Assert\NotBlank(allowNull: true)]
    private ?string $city = null;

    public function __construct(private readonly School $school) {
        $this->uuid = Uuid::uuid4();
    }

    public function getSchoolNumber(): int {
        return $this->schoolNumber;
    }

    public function setSchoolNumber(int $schoolNumber): School {
        $this->schoolNumber = $schoolNumber;
        return $this;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): School {
        $this->name = $name;
        return $this;
    }

    public function getAddress(): string {
        return $this->address;
    }

    public function setAddress(string $address): School {
        $this->address = $address;
        return $this;
    }

    public function getPlz(): ?int {
        return $this->plz;
    }

    public function setPlz(?int $plz): School {
        $this->plz = $plz;
        return $this;
    }

    public function getCity(): ?string {
        return $this->city;
    }

    public function setCity(?string $city): School {
        $this->city = $city;
        return $this;
    }

    public function __toString(): string {
        return sprintf('%d %s (%s, %s)', $this->getSchoolNumber(), $this->getName(), $this->getAddress(), $this->getCity());
    }
}
