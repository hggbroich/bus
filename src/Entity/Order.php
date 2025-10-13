<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: '`order`')]
class Order {

    use IdTrait;
    use UuidTrait;

    #[ORM\ManyToOne(targetEntity: Student::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private Student $student;

    #[ORM\ManyToOne(targetEntity: Country::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private ?Country $country = null;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    private string $phoneNumber;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private string $email;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\Iban]
    private string $iban;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\Bic]
    private string $bic;

    #[ORM\ManyToOne(targetEntity: Ticket::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Ticket $ticket = null;

    /**
     * @var Collection<int, StudentSibling>
     */
    #[ORM\OneToMany(targetEntity: StudentSibling::class, mappedBy: 'order', cascade: ['persist'], orphanRemoval: true)]
    private Collection $siblings;

    use TimestampableOnCreateTrait;
    use TimestampableOnUpdateTrait;

    public function __construct() {
        $this->uuid = Uuid::v4()->toString();
        $this->siblings = new ArrayCollection();
    }

    public function getStudent(): Student {
        return $this->student;
    }

    public function setStudent(Student $student): Order {
        $this->student = $student;
        return $this;
    }

    public function getCountry(): ?Country {
        return $this->country;
    }

    public function setCountry(?Country $country): Order {
        $this->country = $country;
        return $this;
    }

    public function getPhoneNumber(): string {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): Order {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function setEmail(string $email): Order {
        $this->email = $email;
        return $this;
    }

    public function getIban(): string {
        return $this->iban;
    }

    public function setIban(string $iban): Order {
        $this->iban = $iban;
        return $this;
    }

    public function getBic(): string {
        return $this->bic;
    }

    public function setBic(string $bic): Order {
        $this->bic = $bic;
        return $this;
    }

    public function getTicket(): ?Ticket {
        return $this->ticket;
    }

    public function setTicket(?Ticket $ticket): Order {
        $this->ticket = $ticket;
        return $this;
    }

    /**
     * @return Collection<StudentSibling>
     */
    public function getSiblings(): Collection {
        return $this->siblings;
    }

    public function addSibling(StudentSibling $sibling): void {
        $sibling->setOrder($this);
        $this->siblings->add($sibling);
    }

    public function removeSibling(StudentSibling $sibling): void {
        $this->siblings->removeElement($sibling);
    }

}
