<?php

namespace App\Profile\Checks;

use App\Entity\Student;
use App\Profile\ProfileCheckerInterface;
use App\Profile\Violation;
use Override;

class StopIsSetChecker implements ProfileCheckerInterface {

    public const string MessageKey = 'profile.checks.bus_stop';

    #[Override]
    public function check(Student $student): Violation|null {
        if($student->getStop() !== null) {
            return null;
        }

        return new Violation(self::MessageKey);
    }
}
