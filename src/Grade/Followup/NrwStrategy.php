<?php

namespace App\Grade\Followup;

use InvalidArgumentException;
use Override;

readonly class NrwStrategy implements StrategyInterface {

    private const array HasPrefix = [
        '5' => true,
        '6' => true,
        '7' => true,
        '8' => true,
        '9' => true,
        '10' => false,
        'EF' => false,
        'Q1' => false,
        'Q2' => false,
    ];

    private const array HasSuffix = [
        '5' => true,
        '6' => true,
        '7' => true,
        '8' => true,
        '9' => true,
        '10' => true,
        'EF' => false,
        'Q1' => false,
        'Q2' => false,
    ];

    private const array HasNeitherPrefixNorSuffix = [
        'EF',
        'Q1',
        'Q2'
    ];

    private const array Map = [
        '5' => '6',
        '6' => '7',
        '7' => '8',
        '8' => '9',
        '9' => '10',
        '10' => 'EF',
        'EF' => 'Q1',
        'Q1' => 'Q2'
    ];

    #[Override]
    public function resolveFollowupGrade(string|null $grade): string|null {
        if(empty($grade)) {
            return '';
        }

        $currentPrefix = '';
        $currentGrade = '';
        $currentSuffix = '';

        if(!in_array($grade, self::HasNeitherPrefixNorSuffix) && preg_match('/(0*)([0-9]+)\s*([a-z]*)/i', $grade, $matches)) {
            $currentPrefix = $matches[1] ?? '';
            $currentGrade = $matches[2];
            $currentSuffix = $matches[3] ?? '';
        } else {
            $currentGrade = $grade;
        }

        if(!isset(self::Map[$currentGrade])) {
            throw new InvalidArgumentException(sprintf('Ungültige Klasse: %s - kann Folgeklasse nicht bestimmen.', $currentGrade));
        }

        $followupGrade = self::Map[$currentGrade];

        return trim(
            (self::HasPrefix[$followupGrade] ? $currentPrefix : '') .
            $followupGrade .
            (self::HasSuffix[$followupGrade] ? $currentSuffix : '')
        );
    }


    #[Override]
    public function getTranslationKey(): string {
        return 'settings.orders.followup_strategy.nrw';
    }
}
