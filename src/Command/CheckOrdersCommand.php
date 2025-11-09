<?php

namespace App\Command;

use App\Order\Check\OrderChecker;
use App\Settings\OrderSettings;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Scheduler\Attribute\AsCronTask;

#[AsCommand('app:orders:check')]
#[AsCronTask('*/15 * * * *')]
readonly class CheckOrdersCommand {

    public function __construct(
        private OrderSettings $orderSettings,
        private OrderChecker $orderChecker
    ) {

    }

    public function __invoke(SymfonyStyle $io): int {
        if($this->orderSettings->windowStart === null || $this->orderSettings->windowEnd === true) {
            $io->success('Aktuell gibt es kein Bestellungszeitfenster, mache nichts.');
            return Command::SUCCESS;
        }

        $count = $this->orderChecker->checkAllInCurrentWindowAsync();

        $io->success(sprintf('%d Bestellung(en) wurden zur Prüfung eingereiht. Die Bestellungen werden asynchron geprüft.', $count));
        return Command::SUCCESS;
    }
}
