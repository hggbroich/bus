<?php

namespace App\Controller\Admin\Field;

use App\Form\DistanceGoogleMapsType;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class DistanceField implements FieldInterface {

    use FieldTrait;

    public const OPTION_BUTTON_STOP_FIELD = 'stop';
    public const OPTION_BUTTON_PUBLIC_SCHOOL_FIELD = 'publicSchool';

    public static function new(string $propertyName, ?string $label = null): self {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setFormType(DistanceGoogleMapsType::class)
            ->addFormTheme('admin/field/distance_form.html.twig')
            ->addWebpackEncoreEntries('maps-button')
            ->setCustomOption(self::OPTION_BUTTON_STOP_FIELD, null)
            ->setCustomOption(self::OPTION_BUTTON_PUBLIC_SCHOOL_FIELD, null);
    }

    public function setPublicSchoolField(string $publicSchoolField): self {
        $this->setCustomOption(self::OPTION_BUTTON_PUBLIC_SCHOOL_FIELD, $publicSchoolField);
        return $this;
    }

}
