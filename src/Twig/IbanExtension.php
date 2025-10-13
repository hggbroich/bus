<?php

namespace App\Twig;

use App\UI\IbanUtils;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class IbanExtension extends AbstractExtension {
    public function __construct(private readonly IbanUtils $ibanUtils) {

    }

    public function getFilters(): array {
        return [
            new TwigFilter('disguise_iban', [$this, 'disguiseIban'])
        ];
    }

    public function disguiseIban($iban): string {
        return $this->ibanUtils->disguiseIban($iban);
    }
}
