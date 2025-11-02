<?php

namespace App\Settings;

use Jbtronics\SettingsBundle\ParameterTypes\BoolType;
use Jbtronics\SettingsBundle\Settings\Settings;
use Jbtronics\SettingsBundle\Settings\SettingsParameter;
use Jbtronics\SettingsBundle\Settings\SettingsTrait;

#[Settings]
class ProfileSettings {
    use SettingsTrait;

    #[SettingsParameter(type: BoolType::class, label: 'settings.profile.lock_public_school.label')]
    public bool $lockPublicSchoolIfDistanceIsConfirmed = false;

    #[SettingsParameter(type: BoolType::class, label: 'settings.profile.lock_stop.label')]
    public bool $lockStopIfDistanceIsConfirmed = false;

    #[SettingsParameter(type: BoolType::class, label: 'settings.profile.lock_distance_public_school.label')]
    public bool $lockDistanceToPublicSchoolIfConfirmed = false;

    #[SettingsParameter(type: BoolType::class, label: 'settings.profile.lock_distance.label')]
    public bool $lockDistanceToSchoolIfConfirmed = false;
}
