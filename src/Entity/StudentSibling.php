<?php

namespace App\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Override;
use Stringable;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class StudentSibling implements Stringable {
    use IdTrait;

    #[ORM\ManyToOne(targetEntity: Order::class, cascade: ['persist'], inversedBy: 'siblings')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Assert\NotNull]
    private Order|null $order = null;

    #[ORM\ManyToOne(targetEntity: Student::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private Student|null $studentAtSchool = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Assert\NotBlank]
    private string|null $firstname;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Assert\NotBlank]
    private string|null $lastname;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\NotNull]
    private DateTime|null $birthday = null;

    #[ORM\ManyToOne(targetEntity: School::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    #[Assert\NotNull]
    private School|null $school = null;

    public function getOrder(): ?Order {
        return $this->order;
    }

    public function setOrder(?Order $order): StudentSibling {
        $this->order = $order;
        return $this;
    }

    public function getStudentAtSchool(): ?Student {
        return $this->studentAtSchool;
    }

    public function setStudentAtSchool(?Student $studentAtSchool): StudentSibling {
        $this->studentAtSchool = $studentAtSchool;
        return $this;
    }

    public function getFirstname(): string {
        if($this->studentAtSchool !== null) {
            return $this->studentAtSchool->getFirstname();
        }

        return $this->firstname;
    }

    public function setFirstname(string|null $firstname): StudentSibling {
        $this->firstname = $firstname;
        return $this;
    }

    public function getLastname(): string {
        if($this->studentAtSchool !== null) {
            return $this->studentAtSchool->getLastname();
        }

        return $this->lastname;
    }

    public function setLastname(string|null $lastname): StudentSibling {
        $this->lastname = $lastname;
        return $this;
    }

    public function getBirthday(): ?DateTime {
        if($this->studentAtSchool !== null) {
            return $this->studentAtSchool->getBirthday();
        }

        return $this->birthday;
    }

    public function setBirthday(?DateTime $birthday): StudentSibling {
        $this->birthday = $birthday;
        return $this;
    }

    public function getSchool(): ?School {
        return $this->school;
    }

    public function setSchool(?School $school): StudentSibling {
        $this->school = $school;
        return $this;
    }

    #[Override]
    public function __toString(): string {
        return sprintf('%s, %s', $this->getLastname(), $this->getFirstname());
    }
}
