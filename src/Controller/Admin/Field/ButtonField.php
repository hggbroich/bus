<?php

namespace App\Controller\Admin\Field;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;

class ButtonField implements FieldInterface {

    use FieldTrait;

    public const OPTION_BUTTON_URL = 'url';
    public const OPTION_BUTTON_LABEL = 'label';

    public static function new(string $propertyName, ?string $label = null): self {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel('')
            ->setButtonLabel($label)
            ->setTemplatePath('admin/field/button.html.twig')
            ->setCustomOption(self::OPTION_BUTTON_URL, null);
    }

    public function setUrl(string $url): self {
        $this->setCustomOption(self::OPTION_BUTTON_URL, $url);
        return $this;
    }

    public function setButtonLabel(string $label): self {
        $this->setCustomOption(self::OPTION_BUTTON_LABEL, $label);
        return $this;
    }
}
