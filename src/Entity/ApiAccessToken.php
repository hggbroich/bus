<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[UniqueEntity(fields: ['accessToken'])]
class ApiAccessToken {

    use IdTrait;
    use UuidTrait;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'cascade')]
    private User $user;

    #[ORM\Column(type: 'string', length: 64, unique: true)]
    private string $accessToken;

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank()]
    private string $description = '';

    public function __construct() {
        $this->uuid = Uuid::uuid4()->toString();
    }

    public function getUser(): User {
        return $this->user;
    }

    public function setUser(User $user): ApiAccessToken {
        $this->user = $user;
        return $this;
    }

    public function getAccessToken(): string {
        return $this->accessToken;
    }

    public function setAccessToken(string $accessToken): ApiAccessToken {
        $this->accessToken = $accessToken;
        return $this;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function setDescription(string $description): ApiAccessToken {
        $this->description = $description;
        return $this;
    }
}
