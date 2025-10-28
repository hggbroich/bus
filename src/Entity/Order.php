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

    #[ORM\Column(type: Types::STRING, enumType: Gender::class)]
    private Gender $gender = Gender::Other;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    private string $street;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    private string $houseNumber;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private int $plz;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    private string $city;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $busCompanyCustomerId = null;

    #[ORM\ManyToOne(targetEntity: PaymentInterval::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?PaymentInterval $paymentInterval = null;

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

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    private string $depositorFirstname;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    private string $depositorLastname;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank]
    private DateTime $depositorBirthday;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    private string $depositorStreet;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    private string $depositorHouseNumber;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private int $depositorPlz;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    private string $depositorCity;

    #[ORM\ManyToOne(targetEntity: Country::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private ?Country $depositorCountry = null;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    private string $depositorPhoneNumber;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private string $depositorEmail;

    #[ORM\ManyToOne(targetEntity: Ticket::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Ticket $ticket = null;

    #[ORM\ManyToOne(targetEntity: FareLevel::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private FareLevel $fareLevel;

    /**
     * @var Collection<int, StudentSibling>
     */
    #[ORM\OneToMany(targetEntity: StudentSibling::class, mappedBy: 'order', cascade: ['persist'], orphanRemoval: true)]
    private Collection $siblings;

    #[ORM\Column(type: Types::JSON)]
    private array $confirmations = [ ];

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

    public function getGender(): Gender {
        return $this->gender;
    }

    public function setGender(Gender $gender): Order {
        $this->gender = $gender;
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

    public function getBusCompanyCustomerId(): ?string {
        return $this->busCompanyCustomerId;
    }

    public function setBusCompanyCustomerId(?string $busCompanyCustomerId): Order {
        $this->busCompanyCustomerId = $busCompanyCustomerId;
        return $this;
    }

    public function getPaymentInterval(): ?PaymentInterval {
        return $this->paymentInterval;
    }

    public function setPaymentInterval(?PaymentInterval $paymentInterval): Order {
        $this->paymentInterval = $paymentInterval;
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

    public function getDepositorFirstname(): string {
        return $this->depositorFirstname;
    }

    public function setDepositorFirstname(string $depositorFirstname): Order {
        $this->depositorFirstname = $depositorFirstname;
        return $this;
    }

    public function getDepositorLastname(): string {
        return $this->depositorLastname;
    }

    public function setDepositorLastname(string $depositorLastname): Order {
        $this->depositorLastname = $depositorLastname;
        return $this;
    }

    public function getDepositorBirthday(): DateTime {
        return $this->depositorBirthday;
    }

    public function setDepositorBirthday(DateTime $depositorBirthday): Order {
        $this->depositorBirthday = $depositorBirthday;
        return $this;
    }

    public function getDepositorStreet(): string {
        return $this->depositorStreet;
    }

    public function setDepositorStreet(string $depositorStreet): Order {
        $this->depositorStreet = $depositorStreet;
        return $this;
    }

    public function getDepositorHouseNumber(): string {
        return $this->depositorHouseNumber;
    }

    public function setDepositorHouseNumber(string $depositorHouseNumber): Order {
        $this->depositorHouseNumber = $depositorHouseNumber;
        return $this;
    }

    public function getDepositorPlz(): int {
        return $this->depositorPlz;
    }

    public function setDepositorPlz(int $depositorPlz): Order {
        $this->depositorPlz = $depositorPlz;
        return $this;
    }

    public function getDepositorCity(): string {
        return $this->depositorCity;
    }

    public function setDepositorCity(string $depositorCity): Order {
        $this->depositorCity = $depositorCity;
        return $this;
    }

    public function getDepositorCountry(): ?Country {
        return $this->depositorCountry;
    }

    public function setDepositorCountry(?Country $depositorCountry): Order {
        $this->depositorCountry = $depositorCountry;
        return $this;
    }

    public function getDepositorPhoneNumber(): string {
        return $this->depositorPhoneNumber;
    }

    public function setDepositorPhoneNumber(string $depositorPhoneNumber): Order {
        $this->depositorPhoneNumber = $depositorPhoneNumber;
        return $this;
    }

    public function getDepositorEmail(): string {
        return $this->depositorEmail;
    }

    public function setDepositorEmail(string $depositorEmail): Order {
        $this->depositorEmail = $depositorEmail;
        return $this;
    }

    public function getTicket(): ?Ticket {
        return $this->ticket;
    }

    public function setTicket(?Ticket $ticket): Order {
        $this->ticket = $ticket;
        return $this;
    }

    public function getFareLevel(): FareLevel {
        return $this->fareLevel;
    }

    public function setFareLevel(FareLevel $fareLevel): Order {
        $this->fareLevel = $fareLevel;
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

    public function getConfirmations(): array {
        return $this->confirmations;
    }

    public function setConfirmations(array $confirmations): Order {
        $this->confirmations = $confirmations;
        return $this;
    }
}
