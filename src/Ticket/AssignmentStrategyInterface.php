<?php

namespace App\Ticket;

use App\Entity\Order;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(AssignmentStrategyInterface::AUTOCONFIGURE_TAG)]
interface AssignmentStrategyInterface {

    public const string AUTOCONFIGURE_TAG = 'app.ticket.assignment_strategy';

    public function assign(Order $order): void;

    public function getTranslationKey(): string;

    public function getTranslationHelpKey(): string;
}
