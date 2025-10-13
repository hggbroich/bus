<?php

namespace App\Twig;

use App\Entity\Student;
use App\Profile\ProfileCompleteChecker;
use App\Profile\ViolationList;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ProfileExtension extends AbstractExtension {

    public function __construct(private readonly ProfileCompleteChecker $profileChecker) {

    }

    public function getFunctions(): array {
        return [
            new TwigFunction('profileChecker', [$this, 'profileChecker']),
        ];
    }

    public function profileChecker(Student $student): ViolationList {
        return $this->profileChecker->check($student);
    }
}
