<?php

namespace App\Order\Requirements;

use App\Entity\Student;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

readonly class RequirementsChecker {

    /**
     * @param CheckInterface[] $checks
     */
    public function __construct(
        #[AutowireIterator(CheckInterface::AUTOCONFIGURE_TAG)] private iterable $checks
    ) { }

    public function check(Student $student): ViolationList {
        $violations = [ ];

        foreach($this->checks as $check) {
            $violations = array_merge(
                $violations,
                $check->check($student)
            );
        }

        return new ViolationList($violations);
    }
}
