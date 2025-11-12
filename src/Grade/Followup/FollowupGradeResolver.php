<?php

namespace App\Grade\Followup;

use App\Settings\OrderSettings;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

readonly class FollowupGradeResolver {

    /**
     * @param StrategyInterface[] $strategies
     * @param OrderSettings $orderSettings
     */
    public function __construct(
        #[AutowireIterator(StrategyInterface::AUTOCONFIGURE_TAG)] private iterable $strategies,
        private OrderSettings $orderSettings
    ) {

    }

    public function resolveFollowupGrade(string|null $grade): string|null {
        foreach($this->strategies as $strategy) {
            if(get_class($strategy) === $this->orderSettings->followUpStrategy) {
                return $strategy->resolveFollowupGrade($grade);
            }
        }

        return $grade;
    }
}
