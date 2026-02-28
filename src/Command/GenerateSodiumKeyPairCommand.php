<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand('app:generate-keypair')]
readonly class GenerateSodiumKeyPairCommand {
    public function __invoke(SymfonyStyle $io): int {
        $keypair = sodium_crypto_box_keypair();
        $io->section('Keypair (Base64)');
        $io->writeln(base64_encode($keypair));

        $io->section('Public Key (Base64)');
        $io->writeln(base64_encode(sodium_crypto_box_publickey($keypair)));

        $io->section('Secret Key (Base64)');
        $io->writeln(base64_encode(sodium_crypto_box_secretkey($keypair)));

        return Command::SUCCESS;
    }
}
