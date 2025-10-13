<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Stringable;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Ticket implements Stringable {
    use IdTrait;
    use UuidTrait;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    private string $name;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    private string $description;

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

    public function __toString(): string {
        return $this->name;
    }
}
