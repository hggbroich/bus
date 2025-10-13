<?php

namespace App\Profile;

use App\Entity\Student;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.profile.checker')]
interface ProfileCheckerInterface {
    public function check(Student $student): Violation|null;
}
