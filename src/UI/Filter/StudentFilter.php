<?php

namespace App\UI\Filter;

use App\Entity\Student;
use App\Entity\User;
use App\Utils\ArrayUtils;
use Symfony\Component\HttpFoundation\Request;

class StudentFilter {
    public const string QueryParam = 'student';

    public function handle(Request $request, User $user): StudentFilterView {
        $availableStudents = ArrayUtils::createArrayWithKeys(
            $user->getAssociatedStudents()->toArray(),
            fn(Student $student): string => $student->getUuid()
        );
        $currentStudent = null;

        $uuid = $request->query->get(self::QueryParam);
        if(!empty($uuid) && count($availableStudents) > 0) {
            $currentStudent = $availableStudents[$uuid] ?? null;
        }

        return new StudentFilterView($availableStudents, $currentStudent);
    }
}
