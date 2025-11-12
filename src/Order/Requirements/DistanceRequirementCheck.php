<?php

namespace App\Order\Requirements;

use App\Entity\Student;
use App\Grade\Followup\FollowupGradeResolver;
use App\Settings\OrderSettings;
use Override;

class DistanceRequirementCheck implements CheckInterface {

    public function __construct(
        private readonly OrderSettings $orderSettings,
        private readonly FollowupGradeResolver $followUpGradeResolver
    ) {

    }

    #[Override]
    public function check(Student $student): array {
        $distance = $student->getConfirmedDistanceToSchool();
        $followupGrade = $this->followUpGradeResolver->resolveFollowupGrade($student->getGrade());
        $isSekII = in_array($followupGrade, $this->orderSettings->sekIIGrades);

        $minimumDistance = $isSekII ? $this->orderSettings->minimumDistanceSekII : $this->orderSettings->minimumDistanceSekI;

        if($minimumDistance === null) {
            return [ ];
        }

        if($minimumDistance < $distance) {
            return [ ];
        }

        return [
            new Violation('orders.requirements.checks.distance_too_short', [
                '%distance%' => $distance,
                '%minimum%' => $minimumDistance,
            ])
        ];
    }
}
