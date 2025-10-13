<?php

namespace App\Profile\Checks;

use App\Entity\Student;
use App\Profile\ProfileCheckerInterface;
use App\Profile\Violation;

class DistanceToSchoolConfirmedChecker implements ProfileCheckerInterface {

    public const string MessageKey = 'profile.checks.distance_school_confirmed';

    #[Override]
    public function check(Student $student): Violation|null {
        if($student->isDistanceToSchoolConfirmed()) {
            return null;
        }

        return new Violation(self::MessageKey);
    }
}
