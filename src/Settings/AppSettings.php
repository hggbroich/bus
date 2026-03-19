<?php

namespace App\Settings;

use Jbtronics\SettingsBundle\ParameterTypes\StringType;
use Jbtronics\SettingsBundle\Settings\Settings;
use Jbtronics\SettingsBundle\Settings\SettingsParameter;
use Jbtronics\SettingsBundle\Settings\SettingsTrait;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints as Assert;

#[Settings]
class AppSettings {
    use SettingsTrait;

    #[SettingsParameter(type: StringType::class, label: 'settings.app.school_name.label', nullable: false)]
    #[Assert\NotBlank]
    public ?string $schoolName = null;

    #[SettingsParameter(type: StringType::class, label: 'settings.app.to_school_name.label', description: 'settings.app.to_school_name.help', nullable: false)]
    #[Assert\NotBlank]
    public ?string $toSchoolName = null;

    #[SettingsParameter(type: StringType::class, label: 'settings.app.welcome.label', description: 'settings.app.welcome.help', formType: TextareaType::class, formOptions:
        [
            'required' => false,
            'attr' =>
                [
                    'data-ea-code-editor-field' => 'true',
                    'data-language' => 'markdown',
                    'data-tab-size' => 4,
                    'data-indent-with-tabs' => 'false',
                    'data-show-line-numbers' => 'true'
                ]
        ], nullable: true)]
    public ?string $welcomeMessage = null;

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
