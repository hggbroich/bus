<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Stringable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[UniqueEntity(fields: ['priority'])]
#[UniqueEntity(fields: ['defaultExternalId'])]
class Ticket implements Stringable {
    use IdTrait;
    use UuidTrait;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    private string $name;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    private string $description;

    #[ORM\Column(type: Types::INTEGER, unique: true)]
    private int $priority = 1;

    #[ORM\Column(type: Types::STRING, unique: true)]
    private string $defaultExternalId;

    /**
     * @var Collection<TicketPaymentInterval>
     */
    #[ORM\OneToMany(targetEntity: TicketPaymentInterval::class, mappedBy: 'ticket', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[Assert\Valid]
    private Collection $paymentIntervals;

    public function __construct() {
        $this->uuid = Uuid::uuid4();
        $this->paymentIntervals = new ArrayCollection();
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

    public function getPriority(): int {
        return $this->priority;
    }

    public function setPriority(int $priority): Ticket {
        $this->priority = $priority;
        return $this;
    }

    public function getDefaultExternalId(): string {
        return $this->defaultExternalId;
    }

    public function setDefaultExternalId(string $defaultExternalId): Ticket {
        $this->defaultExternalId = $defaultExternalId;
        return $this;
    }

    public function addPaymentInterval(TicketPaymentInterval $paymentInterval): void {
        $paymentInterval->setTicket($this);
        $this->paymentIntervals->add($paymentInterval);
    }

    public function removePaymentInterval(TicketPaymentInterval $paymentInterval): void {
        $this->paymentIntervals->removeElement($paymentInterval);
    }

    /**
     * @return Collection<TicketPaymentInterval>
     */
    public function getPaymentIntervals(): Collection {
        return $this->paymentIntervals;
    }

    public function getExternalIdForPaymentInterval(PaymentInterval|null $paymentInterval): ?string {
        if($paymentInterval === null) {
            return $this->getDefaultExternalId();
        }

        foreach($this->paymentIntervals as $ticketPaymentInterval) {
            if($ticketPaymentInterval->getPaymentInterval() === $paymentInterval) {
                return $ticketPaymentInterval->getExternalId();
            }
        }

        return $this->getDefaultExternalId();
    }

    public function __toString(): string {
        return $this->name;
    }
}
