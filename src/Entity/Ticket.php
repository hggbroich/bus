<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Stringable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[UniqueEntity(fields: ['priority'])]
class Ticket implements Stringable {
    use IdTrait;
    use UuidTrait;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    private string $name;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    private string $description;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    private string $externalId;

    #[ORM\Column(type: Types::INTEGER, unique: true)]
    private int $priority = 1;

    public function __construct() {
        $this->uuid = Uuid::uuid4();
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): Ticket {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function setDescription(string $description): Ticket {
        $this->description = $description;
        return $this;
    }

    public function getExternalId(): string {
        return $this->externalId;
    }

    public function setExternalId(string $externalId): Ticket {
        $this->externalId = $externalId;
        return $this;
    }

    public function getPriority(): int {
        return $this->priority;
    }

    public function setPriority(int $priority): Ticket {
        $this->priority = $priority;
        return $this;
    }

    public function __toString(): string {
        return $this->name;
    }
}
