<?php

namespace App\Entity;

use App\Doctrine\Encryption\Attribute\Encrypt;
use App\Doctrine\Encryption\Preview\IbanPreviewGenerator;
use DateTime;
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

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    private string $firstname;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    private string $lastname;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank]
    private DateTime $birthday;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank(allowNull: true)]
    private ?string $street = null;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank(allowNull: true)]
    private ?string $houseNumber = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $plz = null;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank(allowNull: true)]
    private ?string $city = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $busCompanyCustomerId = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $sgb12 = false;

    #[ORM\ManyToOne(targetEntity: Stop::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Stop $stop = null;

    #[ORM\ManyToOne(targetEntity: School::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?School $publicSchool = null;

    #[ORM\Column(type: Types::INTEGER)]
    #[Assert\GreaterThanOrEqual(0)]
    private int $confirmedDistanceToPublicSchool = 0;

    #[ORM\Column(type: Types::INTEGER)]
    #[Assert\GreaterThanOrEqual(0)]
    private int $confirmedDistanceToSchool = 0;

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
    #[Encrypt(
        encryptedPropertyName: 'encryptedIban',
        previewGenerator: IbanPreviewGenerator::class,
        preventEncryptionValuePropertyName: 'preventEncryptionValue'
    )]
    #[Assert\Iban(groups: ['iban'])]
    private string $iban;

    #[ORM\Column(type: Types::TEXT)]
    private string $encryptedIban;

    private string|null $preventEncryptionValue = null;

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

    public function getFirstname(): string {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): Order {
        $this->firstname = $firstname;
        return $this;
    }

    public function getLastname(): string {
        return $this->lastname;
    }

    public function setLastname(string $lastname): Order {
        $this->lastname = $lastname;
        return $this;
    }

    public function getBirthday(): DateTime {
        return $this->birthday;
    }

    public function setBirthday(DateTime $birthday): Order {
        $this->birthday = $birthday;
        return $this;
    }

    public function getStreet(): ?string {
        return $this->street;
    }

    public function setStreet(?string $street): Order {
        $this->street = $street;
        return $this;
    }

    public function getHouseNumber(): ?string {
        return $this->houseNumber;
    }

    public function setHouseNumber(?string $houseNumber): Order {
        $this->houseNumber = $houseNumber;
        return $this;
    }

    public function getPlz(): ?int {
        return $this->plz;
    }

    public function setPlz(?int $plz): Order {
        $this->plz = $plz;
        return $this;
    }

    public function getCity(): ?string {
        return $this->city;
    }

    public function setCity(?string $city): Order {
        $this->city = $city;
        return $this;
    }

    public function getBusCompanyCustomerId(): ?int {
        return $this->busCompanyCustomerId;
    }

    public function setBusCompanyCustomerId(?int $busCompanyCustomerId): Order {
        $this->busCompanyCustomerId = $busCompanyCustomerId;
        return $this;
    }

    public function isSgb12(): bool {
        return $this->sgb12;
    }

    public function setSgb12(bool $sgb12): Order {
        $this->sgb12 = $sgb12;
        return $this;
    }

    public function getStop(): ?Stop {
        return $this->stop;
    }

    public function setStop(?Stop $stop): Order {
        $this->stop = $stop;
        return $this;
    }

    public function getPublicSchool(): ?School {
        return $this->publicSchool;
    }

    public function setPublicSchool(?School $publicSchool): Order {
        $this->publicSchool = $publicSchool;
        return $this;
    }

    public function getConfirmedDistanceToPublicSchool(): int {
        return $this->confirmedDistanceToPublicSchool;
    }

    public function setConfirmedDistanceToPublicSchool(int $confirmedDistanceToPublicSchool): Order {
        $this->confirmedDistanceToPublicSchool = $confirmedDistanceToPublicSchool;
        return $this;
    }

    public function getConfirmedDistanceToSchool(): int {
        return $this->confirmedDistanceToSchool;
    }

    public function setConfirmedDistanceToSchool(int $confirmedDistanceToSchool): Order {
        $this->confirmedDistanceToSchool = $confirmedDistanceToSchool;
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

    public function getEncryptedIban(): string {
        return $this->encryptedIban;
    }

    public function setEncryptedIban(string $encryptedIban): Order {
        $this->encryptedIban = $encryptedIban;
        return $this;
    }

    public function getPreventEncryptionValue(): ?string {
        return $this->preventEncryptionValue;
    }

    public function setPreventEncryptionValue(?string $preventEncryptionValue): Order {
        $this->preventEncryptionValue = $preventEncryptionValue;
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
