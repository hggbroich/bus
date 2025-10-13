<?php

namespace App\UI\Filter;

use App\Entity\Student;

readonly class StudentFilterView {

    /**
     * @param Student[] $students
     * @param Student|null $currentStudent
     */
    public function __construct(
        public array $students,
        public Student|null $currentStudent
    ) {

    }

    /**
     * @return Student[]
     */
    public function getCurrentOrDefault(): array {
        if($this->currentStudent !== null) {
            return [ $this->currentStudent ];
        }

        return $this->students;
    }
}
