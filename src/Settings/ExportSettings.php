<?php

namespace App\Settings;

use App\Settings\Type\KeyValueType;
use Jbtronics\SettingsBundle\ParameterTypes\ArrayType;
use Jbtronics\SettingsBundle\Settings\Settings;
use Jbtronics\SettingsBundle\Settings\SettingsParameter;
use Jbtronics\SettingsBundle\Settings\SettingsTrait;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

#[Settings]
class ExportSettings {
    use SettingsTrait;

    #[SettingsParameter(label: 'settings.export.student_id_prefix.label', description: 'settings.export.student_id_prefix.help', formOptions: [
        'required' => false,
    ], nullable: false)]
    public string $studentIdPrefix = '';

    /**
     * @var array<KeyValuePair>
     */
    #[SettingsParameter(type: KeyValueType::class, label: 'settings.export.additional_columns.label', description: 'settings.export.additional_columns.help', formOptions: [
        'required' => false,
    ], nullable: false)]
    public array $additionalColumns = [ ];
}
