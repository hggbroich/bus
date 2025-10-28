<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Stringable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[UniqueEntity(fields: ['externalId'])]
class Student implements Stringable {
    use IdTrait;
    use UuidTrait;

    #[ORM\Column(type: Types::STRING, length: 255, unique: true)]
    #[Assert\NotBlank]
    private string $externalId;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    private string $status;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    private string $firstname;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    private string $lastname;

    #[ORM\Column(type: Types::STRING, unique: true)]
    #[Assert\NotBlank(allowNull: true)]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank(allowNull: true)]
    private ?string $grade = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank]
    private DateTime $birthday;

    #[ORM\Column(type: Types::STRING, enumType: Gender::class)]
    private Gender $gender = Gender::Other;

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

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private DateTime $entranceDate;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?DateTime $leaveDate = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $busCompanyCustomerId = null;

    #[ORM\ManyToOne(targetEntity: PaymentInterval::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
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
    private int $distanceToPublicSchool = 0;

    #[ORM\Column(type: Types::INTEGER)]
    #[Assert\GreaterThanOrEqual(0)]
    private int $confirmedDistanceToPublicSchool = 0;

    #[ORM\Column(type: Types::INTEGER)]
    #[Assert\GreaterThanOrEqual(0)]
    private int $distanceToSchool = 0;

    #[ORM\Column(type: Types::INTEGER)]
    #[Assert\GreaterThanOrEqual(0)]
    private int $confirmedDistanceToSchool = 0;

    /** @var Collection<int, Order> */
    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'student', cascade: ['persist', 'remove'], orphanRemoval: true)]
    public Collection $orders;

    use TimestampableOnCreateTrait;
    use TimestampableOnUpdateTrait;

    public function __construct() {
        $this->uuid = Uuid::uuid4();
        $this->orders = new ArrayCollection();
    }

    public function getExternalId(): string {
        return $this->externalId;
    }

    public function setExternalId(string $externalId): Student {
        $this->externalId = $externalId;
        return $this;
    }

    public function getStatus(): string {
        return $this->status;
    }

    public function setStatus(string $status): Student {
        $this->status = $status;
        return $this;
    }

    public function getFirstname(): string {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): Student {
        $this->firstname = $firstname;
        return $this;
    }

    public function getLastname(): string {
        return $this->lastname;
    }

    public function setLastname(string $lastname): Student {
        $this->lastname = $lastname;
        return $this;
    }

    public function getEmail(): ?string {
        return $this->email;
    }

    public function setEmail(?string $email): Student {
        $this->email = $email;
        return $this;
    }

    public function getGrade(): ?string {
        return $this->grade;
    }

    public function setGrade(?string $grade): Student {
        $this->grade = $grade;
        return $this;
    }

    public function getBirthday(): DateTime {
        return $this->birthday;
    }

    public function setBirthday(DateTime $birthday): Student {
        $this->birthday = $birthday;
        return $this;
    }

    public function getGender(): Gender {
        return $this->gender;
    }

    public function setGender(Gender $gender): Student {
        $this->gender = $gender;
        return $this;
    }

    public function getStreet(): ?string {
        return $this->street;
    }

    public function setStreet(?string $street): Student {
        $this->street = $street;
        return $this;
    }

    public function getHouseNumber(): ?string {
        return $this->houseNumber;
    }

    public function setHouseNumber(?string $houseNumber): Student {
        $this->houseNumber = $houseNumber;
        return $this;
    }

    public function getPlz(): ?int {
        return $this->plz;
    }

    public function setPlz(?int $plz): Student {
        $this->plz = $plz;
        return $this;
    }

    public function getCity(): ?string {
        return $this->city;
    }

    public function setCity(?string $city): Student {
        $this->city = $city;
        return $this;
    }

    public function getEntranceDate(): DateTime {
        return $this->entranceDate;
    }

    public function setEntranceDate(DateTime $entranceDate): Student {
        $this->entranceDate = $entranceDate;
        return $this;
    }

    public function getLeaveDate(): ?DateTime {
        return $this->leaveDate;
    }

    public function setLeaveDate(?DateTime $leaveDate): Student {
        $this->leaveDate = $leaveDate;
        return $this;
    }

    public function getBusCompanyCustomerId(): ?int {
        return $this->busCompanyCustomerId;
    }

    public function setBusCompanyCustomerId(?int $busCompanyCustomerId): Student {
        $this->busCompanyCustomerId = $busCompanyCustomerId;
        return $this;
    }

    public function getPaymentInterval(): ?PaymentInterval {
        return $this->paymentInterval;
    }

    public function setPaymentInterval(?PaymentInterval $paymentInterval): Student {
        $this->paymentInterval = $paymentInterval;
        return $this;
    }

    public function isSgb12(): bool {
        return $this->sgb12;
    }

    public function setSgb12(bool $sgb12): Student {
        $this->sgb12 = $sgb12;
        return $this;
    }

    public function getStop(): ?Stop {
        return $this->stop;
    }

    public function setStop(?Stop $stop): Student {
        $this->stop = $stop;
        return $this;
    }

    public function getPublicSchool(): ?School {
        return $this->publicSchool;
    }

    public function setPublicSchool(?School $publicSchool): Student {
        $this->publicSchool = $publicSchool;
        return $this;
    }

    public function getDistanceToPublicSchool(): int {
        return $this->distanceToPublicSchool;
    }

    public function setDistanceToPublicSchool(int $distanceToPublicSchool): Student {
        $this->distanceToPublicSchool = $distanceToPublicSchool;
        return $this;
    }

    public function getDistanceToSchool(): int {
        return $this->distanceToSchool;
    }

    public function setDistanceToSchool(int $distanceToSchool): Student {
        $this->distanceToSchool = $distanceToSchool;
        return $this;
    }

    public function getConfirmedDistanceToPublicSchool(): int {
        return $this->confirmedDistanceToPublicSchool;
    }

    public function setConfirmedDistanceToPublicSchool(int $confirmedDistanceToPublicSchool): Student {
        $this->confirmedDistanceToPublicSchool = $confirmedDistanceToPublicSchool;
        return $this;
    }

    public function isDistanceToPublicSchoolConfirmed(): bool {
        return $this->confirmedDistanceToPublicSchool > 0;
    }

    public function getConfirmedDistanceToSchool(): int {
        return $this->confirmedDistanceToSchool;
    }

    public function setConfirmedDistanceToSchool(int $confirmedDistanceToSchool): Student {
        $this->confirmedDistanceToSchool = $confirmedDistanceToSchool;
        return $this;
    }

    public function isDistanceToSchoolConfirmed(): bool {
        return $this->confirmedDistanceToSchool > 0;
    }

    public function getOrders(): Collection {
        return $this->orders;
    }

    public function __toString(): string {
        return sprintf('%s, %s (%s)', $this->getLastname(), $this->getFirstname(), $this->getGrade());
    }
}
