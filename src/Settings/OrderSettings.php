<?php

namespace App\Settings;

use App\Entity\School;
use App\Form\SchoolType;
use App\Grade\Followup\NrwStrategy;
use App\Settings\Type\AssignmentStrategyType;
use App\Settings\Type\FollowupGradeStrategyType;
use App\Settings\Type\KeyValueType;
use App\Ticket\AssignmentStrategy\BirthdayAssignmentStrategy;
use DateTime;
use Jbtronics\SettingsBundle\ParameterTypes\ArrayType;
use Jbtronics\SettingsBundle\ParameterTypes\DatetimeType;
use Jbtronics\SettingsBundle\ParameterTypes\FloatType;
use Jbtronics\SettingsBundle\ParameterTypes\StringType;
use Jbtronics\SettingsBundle\Settings\Settings;
use Jbtronics\SettingsBundle\Settings\SettingsParameter;
use Jbtronics\SettingsBundle\Settings\SettingsTrait;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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

    #[SettingsParameter(type: ArrayType::class, label: 'settings.orders.confirmations.label', description: 'settings.orders.confirmations.help', options: ['type' => StringType::class ], formType: CollectionType::class, formOptions: [
        'entry_type' => TextareaType::class,
        'allow_add' => true,
        'allow_delete' => true,
        'row_attr' => [
            'class' => 'field-collection'
        ],
        'entry_options' => [
            'attr' => [
                'data-ea-code-editor-field' => 'true',
                'data-language' => 'markdown',
                'data-tab-size' => 4,
                'data-indent-with-tabs' => 'false',
                'data-show-line-numbers' => 'true'
            ]
        ]
    ], nullable: false)]
    public array $confirmations = [ ];

    #[SettingsParameter(type: FloatType::class, label: 'settings.orders.minimum_distance_sekI.label', description: 'settings.orders.minimum_distance_sekI.help', nullable: true)]
    public float|null $minimumDistanceSekI = null;

    #[SettingsParameter(type: FloatType::class, label: 'settings.orders.minimum_distance_sekII.label', description: 'settings.orders.minimum_distance_sekII.help', nullable: true)]
    public float|null $minimumDistanceSekII = null;

    #[SettingsParameter(type: ArrayType::class, label: 'settings.orders.sekII_grades.label', description: 'settings.orders.sekII_grades.help', options: ['type' => StringType::class ], formType: CollectionType::class, formOptions: [
        'entry_type' => TextType::class,
        'allow_add' => true,
        'allow_delete' => true,
        'row_attr' => [
            'class' => 'field-collection'
        ]
    ], nullable: false)]
    public array $sekIIGrades = [ ];

    #[SettingsParameter(type: FollowupGradeStrategyType::class, label: 'settings.orders.followup_strategy.label', description: 'settings.orders.followup_strategy.help', nullable: false)]
    public string $followUpStrategy = NrwStrategy::class;

    #[SettingsParameter(label: 'settings.orders.effective_date.label', description: 'settings.orders.effective_date.help', formType: DateType::class, nullable: false)]
    public DateTime|null $effectiveDate = null;

    #[SettingsParameter(type: AssignmentStrategyType::class, label: 'settings.orders.ticket_assignment.label')]
    public string $assignmentStrategy = BirthdayAssignmentStrategy::class;
}
