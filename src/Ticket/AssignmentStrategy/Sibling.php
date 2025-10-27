<?php

namespace App\Ticket\AssignmentStrategy;

use App\Entity\Student;
use DateTime;

/**
 * @internal
 */
readonly class Sibling {
    public function __construct(
        public string $firstname,
        public string $lastname,
        public DateTime $birthday,
        public Student|null $student
    ) {

    }
}
