<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Override;
use Stringable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[UniqueEntity(fields: ['ticket', 'paymentInterval'])]
#[ORM\UniqueConstraint(fields: ['ticket', 'paymentInterval'])]
#[UniqueEntity(fields: 'externalId')]
class TicketPaymentInterval implements Stringable {

    use IdTrait;

    #[ORM\ManyToOne(targetEntity: Ticket::class, inversedBy: "paymentIntervals")]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Assert\NotNull]
    private Ticket $ticket;

    #[ORM\ManyToOne(targetEntity: PaymentInterval::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Assert\NotNull]
    private PaymentInterval $paymentInterval;

    #[ORM\Column(type: Types::STRING, unique: true)]
    #[Assert\NotBlank]
    private string $externalId;

    public function getTicket(): Ticket {
        return $this->ticket;
    }

    public function setTicket(Ticket $ticket): TicketPaymentInterval {
        $this->ticket = $ticket;
        return $this;
    }

    public function getPaymentInterval(): PaymentInterval {
        return $this->paymentInterval;
    }

    public function setPaymentInterval(PaymentInterval $paymentInterval): TicketPaymentInterval {
        $this->paymentInterval = $paymentInterval;
        return $this;
    }

    public function getExternalId(): string {
        return $this->externalId;
    }

    public function setExternalId(string $externalId): TicketPaymentInterval {
        $this->externalId = $externalId;
        return $this;
    }

    #[Override]
    public function __toString(): string {
        return sprintf('%s (ID: %s)', $this->paymentInterval->name, $this->externalId);
    }
}
