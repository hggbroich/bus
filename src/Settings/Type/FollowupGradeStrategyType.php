<?php

namespace App\Settings\Type;

use App\Grade\Followup\StrategyInterface;
use Jbtronics\SettingsBundle\Metadata\ParameterMetadata;
use Jbtronics\SettingsBundle\ParameterTypes\ParameterTypeInterface;
use Jbtronics\SettingsBundle\ParameterTypes\ParameterTypeWithFormDefaultsInterface;
use Override;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class FollowupGradeStrategyType implements ParameterTypeInterface, ParameterTypeWithFormDefaultsInterface {

    public function __construct(
        private TranslatorInterface $translator,
        #[AutowireIterator(StrategyInterface::AUTOCONFIGURE_TAG)] private iterable $strategies
    ) {

    }

    #[Override]
    public function convertPHPToNormalized(mixed $value, ParameterMetadata $parameterMetadata): int|string|float|bool|array|null {
        return trim($value);
    }

    #[Override]
    public function convertNormalizedToPHP(float|int|bool|array|string|null $value, ParameterMetadata $parameterMetadata): mixed {
        return $value;
    }

    #[Override]
    public function getFormType(ParameterMetadata $parameterMetadata): string {
        return ChoiceType::class;
    }

    #[Override]
    public function configureFormOptions(OptionsResolver $resolver, ParameterMetadata $parameterMetadata): void {
        $choices = [ ];

        foreach($this->strategies as $strategy) {
            $choices[$this->translator->trans($strategy->getTranslationKey())] = get_class($strategy);
        }

        $resolver->setDefault('choices', $choices);
        $resolver->setDefault('expanded', true);
    }
}
