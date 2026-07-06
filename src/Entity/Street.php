<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

#[ORM\Entity]
class Street {

    use IdTrait;
    use UuidTrait;

    #[ORM\ManyToOne(targetEntity: City::class)]
    public City $city;

    #[ORM\Column(type: Types::STRING, length: 255)]
    public string $name;

    function __construct() {
        $this->uuid = Uuid::uuid4();
    }

    public function getCity(): City {
        return $this->city;
    }

    public function setCity(City $city): Street {
        $this->city = $city;
        return $this;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): Street {
        $this->name = $name;
        return $this;
    }
}
