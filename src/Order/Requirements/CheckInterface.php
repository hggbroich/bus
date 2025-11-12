<?php

namespace App\Order\Requirements;

use App\Entity\Student;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(self::AUTOCONFIGURE_TAG)]
interface CheckInterface {

    public const string AUTOCONFIGURE_TAG = 'app.order.requirement';

    /**
     * @param Student $student
     * @return Violation[]
     */
    public function check(Student $student): array;
}
