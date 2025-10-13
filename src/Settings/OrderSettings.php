<?php

namespace App\Settings;

use App\Entity\School;
use App\Form\SchoolType;
use DateTime;
use Jbtronics\SettingsBundle\ParameterTypes\ArrayType;
use Jbtronics\SettingsBundle\ParameterTypes\IntType;
use Jbtronics\SettingsBundle\ParameterTypes\StringType;
use Jbtronics\SettingsBundle\Settings\Settings;
use Jbtronics\SettingsBundle\Settings\SettingsParameter;
use Jbtronics\SettingsBundle\Settings\SettingsTrait;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;

#[Settings]
class OrderSettings {
    use SettingsTrait;

    #[SettingsParameter(type: ArrayType::class, label: 'settings.orders.status.label', description: 'settings.orders.status.help', options: ['type' => StringType::class ], formType: CollectionType::class, formOptions: [
        'entry_type' => TextType::class,
        'allow_add' => true,
        'allow_delete' => true,
        'row_attr' => [
            'class' => 'field-collection'
        ]
    ], nullable: false)]
    public array $requiredStatusForOrder = [ ];

    #[SettingsParameter(type: EntityType::class, label: 'settings.orders.school.label', description: 'settings.orders.school.help', options: ['class' => School::class], formType: SchoolType::class, nullable: true, cloneable: false)]
    #[Assert\NotNull]
    public School|null $school = null;

    #[SettingsParameter(label: 'settings.orders.window.start.label', description: 'settings.orders.window.start.help', formType: DateType::class, nullable: true)]
    public DateTime|null $windowStart = null;

    #[SettingsParameter(label: 'settings.orders.window.end.label', description: 'settings.orders.window.end.help', formType: DateType::class, nullable: true)]
    #[Assert\GreaterThan(propertyPath: 'windowStart')]
    public DateTime|null $windowEnd = null;
}
