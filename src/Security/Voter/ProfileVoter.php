<?php

namespace App\Security\Voter;

use App\Entity\Student;
use App\Entity\User;
use Override;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ProfileVoter extends Voter {

    public const string ViewList = 'profile';
    public const string Update = 'update';

    #[Override]
    protected function supports(string $attribute, mixed $subject): bool {
        return $attribute === self::ViewList
            || ($attribute === self::Update && $subject instanceof Student);
    }

    #[Override]
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, Vote|null $vote = null): bool {
        switch($attribute) {
            case self::ViewList:
                return $this->canViewProfile($token);

            case self::Update:
                return $this->canUpdate($subject, $token);
        }
    }

    private function canViewProfile(TokenInterface $token): bool {
        $user = $token->getUser();

        if(!$user instanceof User) {
            return false;
        }

        return $user->getAssociatedStudents()->count() > 0;
    }

    private function canUpdate(Student $student, TokenInterface $token): bool {
        $user = $token->getUser();

        if(!$user instanceof User) {
            return false;
        }

        return $user->getAssociatedStudents()->contains($student);
    }
}
