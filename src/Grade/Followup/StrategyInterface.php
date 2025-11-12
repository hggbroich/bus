<?php

namespace App\Grade\Followup;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(self::AUTOCONFIGURE_TAG)]
interface StrategyInterface {

    public const string AUTOCONFIGURE_TAG = 'app.grade.followup';

    public function resolveFollowupGrade(string|null $grade): string|null;

    public function getTranslationKey(): string;
}
