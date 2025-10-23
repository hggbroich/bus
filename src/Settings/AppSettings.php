<?php

namespace App\Settings;

use Jbtronics\SettingsBundle\ParameterTypes\StringType;
use Jbtronics\SettingsBundle\Settings\Settings;
use Jbtronics\SettingsBundle\Settings\SettingsParameter;
use Jbtronics\SettingsBundle\Settings\SettingsTrait;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

#[Settings]
class AppSettings {
    use SettingsTrait;

    #[SettingsParameter(type: StringType::class, label: 'settings.app.custom_css.label', description: 'settings.app.custom_css.help', formType: TextareaType::class, formOptions:
        [
            'required' => false,
            'attr' =>
                [
                    'data-ea-code-editor-field' => 'true',
                    'data-language' => 'css',
                    'data-tab-size' => 4,
                    'data-indent-with-tabs' => 'false',
                    'data-show-line-numbers' => 'true'
                ]
        ],
        nullable: true)]
    public ?string $customCss = null;
}
