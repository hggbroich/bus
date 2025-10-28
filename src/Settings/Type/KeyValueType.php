<?php

namespace App\Settings\Type;

use App\Settings\Form\KeyValuePairType;
use App\Settings\KeyValuePair;
use Jbtronics\SettingsBundle\Metadata\ParameterMetadata;
use Jbtronics\SettingsBundle\ParameterTypes\ParameterTypeInterface;
use Jbtronics\SettingsBundle\ParameterTypes\ParameterTypeWithFormDefaultsInterface;
use Override;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Serializer\SerializerInterface;

readonly class KeyValueType implements ParameterTypeInterface, ParameterTypeWithFormDefaultsInterface {

    public function __construct(
        private SerializerInterface $serializer
    ) {

    }

    #[Override]
    public function convertPHPToNormalized(mixed $value, ParameterMetadata $parameterMetadata): int|string|float|bool|array|null {
        return $this->serializer->serialize($value, 'json');
    }

    #[Override]
    public function convertNormalizedToPHP(float|int|bool|array|string|null $value, ParameterMetadata $parameterMetadata): mixed {
        return $this->serializer->deserialize($value, type: KeyValuePair::class . '[]', format: 'json');
    }

    #[Override]
    public function getFormType(ParameterMetadata $parameterMetadata): string {
        return CollectionType::class;
    }

    #[Override]
    public function configureFormOptions(OptionsResolver $resolver, ParameterMetadata $parameterMetadata): void {
        $resolver->setDefault('entry_type', KeyValuePairType::class);
        $resolver->setDefault('allow_add', true);
        $resolver->setDefault('allow_delete', true);
        $resolver->setDefault('row_attr', [ 'class' => 'field-collection' ]);
    }
}
