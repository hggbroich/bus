<?php

namespace App\Order\Requirements;

use App\Entity\Student;
use App\Profile\ProfileCompleteChecker;
use Override;

readonly class ProfileCompletedCheck implements CheckInterface {

    public function __construct(
        private ProfileCompleteChecker $profileCompleteChecker
    ) {

    }

    #[Override]
    public function check(Student $student): array {
        if(!$this->profileCompleteChecker->isProfileCompletedByParents($student)) {
            return [
                new Violation('orders.requirements.checks.profile_incomplete')
            ];
        }

        return [ ];
    }
}
