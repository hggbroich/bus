<?php

namespace App\Profile\Checks;

use App\Entity\Student;
use App\Profile\ProfileCheckerInterface;
use App\Profile\Violation;
use Override;

readonly class DistanceToPublicSchoolChecker implements ProfileCheckerInterface {

    public const string MessageKey = 'profile.checks.distance_public_school';

    #[Override]
    public function check(Student $student): Violation|null {
        if($student->getDistanceToSchool() > 0) {
            return null;
        }

        return new Violation(self::MessageKey);
    }
}
