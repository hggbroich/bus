<?php

namespace App\Profile\Checks;

use App\Entity\Student;
use App\Profile\ProfileCheckerInterface;
use App\Profile\Violation;
use Override;

class PublicSchoolChecker implements ProfileCheckerInterface {

    public const string MessageKey = 'profile.checks.public_school';

    #[Override]
    public function check(Student $student): Violation|null {
        if($student->getPublicSchool() !== null) {
            return null;
        }

        return new Violation(self::MessageKey);
    }
}
