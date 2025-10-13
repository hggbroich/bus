<?php

namespace App\Profile\Checks;

use App\Entity\Student;
use App\Profile\ProfileCheckerInterface;
use App\Profile\Violation;

class DistanceToPublicSchoolConfirmedChecker implements ProfileCheckerInterface {

    public const string MessageKey = 'profile.checks.distance_public_school_confirmed';

    #[Override]
    public function check(Student $student): Violation|null {
        if($student->isDistanceToPublicSchoolConfirmed()) {
            return null;
        }

        return new Violation(self::MessageKey);
    }
}
