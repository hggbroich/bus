<?php

namespace App\Settings\Type;

use App\Import\Cities\OpenPlzApiClient;
use Jbtronics\SettingsBundle\Metadata\ParameterMetadata;
use Jbtronics\SettingsBundle\ParameterTypes\ParameterTypeInterface;
use Jbtronics\SettingsBundle\ParameterTypes\ParameterTypeWithFormDefaultsInterface;
use OpenPlzApi\DE\FederalState;
use Override;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

readonly class FederalStateChoiceType implements ParameterTypeInterface, ParameterTypeWithFormDefaultsInterface {
    public function __construct(
        private OpenPlzApiClient $plzApiClient
    ) { }

    #[Override]
    public function getFormType(ParameterMetadata $parameterMetadata): string {
        return ChoiceType::class;
    }

    #[Override]
    public function configureFormOptions(OptionsResolver $resolver, ParameterMetadata $parameterMetadata): void {
        $choices = [ ];

        /** @var FederalState $state */
        foreach($this->plzApiClient->getFederalStates() as $state) {
            $choices[$state->name] = $state->key;
        }

        $resolver->setDefault('choices', $choices);
        $resolver->setDefault('expanded', true);
    }

    #[Override]
    public function convertPHPToNormalized(mixed $value, ParameterMetadata $parameterMetadata): int|string|float|bool|array|null {
        return trim($value);
    }

    #[Override]
    public function convertNormalizedToPHP(float|int|bool|array|string|null $value, ParameterMetadata $parameterMetadata): mixed {
        return $value;
    }
}
