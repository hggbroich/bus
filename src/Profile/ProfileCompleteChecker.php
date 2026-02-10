<?php

namespace App\Profile;

use App\Entity\Student;
use App\Profile\Checks\DistanceToPublicSchoolChecker;
use App\Profile\Checks\DistanceToPublicSchoolConfirmedChecker;
use App\Profile\Checks\DistanceToSchoolChecker;
use App\Profile\Checks\DistanceToSchoolConfirmedChecker;
use App\Profile\Checks\PublicSchoolChecker;
use App\Profile\Checks\StopIsSetChecker;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

readonly class ProfileCompleteChecker {

    /**
     * @param ProfileCheckerInterface[] $checkers
     */
    public function __construct(#[AutowireIterator('app.profile.checker')] private iterable $checkers) {

    }

    public function isProfileCompletedByParents(Student $student): bool {
        $violationList = $this->check(
            $student,
            [
                DistanceToPublicSchoolChecker::class,
                DistanceToSchoolChecker::class,
                PublicSchoolChecker::class,
                StopIsSetChecker::class
            ]
        );

        return $violationList->hasViolations() === false;
    }

    public function check(Student $student, array $checkersFqcn = [ ]): ViolationList {
        $results = [ ];

        foreach($this->checkers as $checker) {
            if(!empty($checkersFqcn) && !in_array(get_class($checker), $checkersFqcn)) {
                continue;
            }

            $result = $checker->check($student);

            if($result instanceof Violation) {
                $results[] = $result;
            }
        }

        $messageKey = 'profile.checks.ok';

        if(count($results) > 0) {
            $messageKey = 'profile.checks.not_ok';

            if($this->isPending($results)) {
                $messageKey = 'profile.checks.pending';
            }
        }

        return new ViolationList($results, $messageKey);
    }

    /**
     * @param Violation[] $violations
     * @return bool
     */
    private function isPending(array $violations): bool {
        $keys = array_map(fn(Violation $violation): string => $violation->messageKey, $violations);
        $pendingKeys = [
            DistanceToSchoolConfirmedChecker::MessageKey,
            DistanceToPublicSchoolConfirmedChecker::MessageKey
        ];

        $intersect = array_intersect($keys, $pendingKeys);

        if(count($violations) === count($intersect)) {
            return true;
        }

        return false;
    }
}
