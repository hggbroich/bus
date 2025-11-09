<?php

namespace App\Command;

use App\Order\Check\CheckOrderMessage;
use App\Order\Check\OrderChecker;
use App\Repository\OrderRepositoryInterface;
use App\Settings\OrderSettings;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Scheduler\Attribute\AsCronTask;

#[AsCommand('app:orders:check')]
#[AsCronTask('*/15 * * * *')]
readonly class CheckOrdersCommand {

    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private OrderSettings $orderSettings,
        private MessageBusInterface $messageBus
    ) {

    }

    public function __invoke(SymfonyStyle $io): int {
        if($this->orderSettings->windowStart === null || $this->orderSettings->windowEnd === true) {
            $io->success('Aktuell gibt es kein Bestellungszeitfenster, mache nichts.');
            return Command::SUCCESS;
        }

        $orders = $this->orderRepository->findAllRange(
            $this->orderSettings->windowStart,
            $this->orderSettings->windowEnd
        );

        $io->section(sprintf('Bearbeite %d Bestellung(en)', count($orders)));

        foreach($orders as $order) {
            $this->messageBus->dispatch(new CheckOrderMessage($order->getId()));
        }

        $io->success('Alle Bestellungen wurden zur Prüfung eingereiht. Die Bestellungen werden asynchron geprüft.');

        return Command::SUCCESS;
    }
}
