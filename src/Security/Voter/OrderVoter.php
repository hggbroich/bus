<?php

namespace App\Security\Voter;

use App\Entity\Order;
use App\Entity\Student;
use App\Entity\User;
use App\Repository\OrderRepositoryInterface;
use App\Settings\OrderSettings;
use LogicException;
use Override;
use Symfony\Component\Clock\ClockInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class OrderVoter extends Voter {

    public const string ANY = 'any-order';

    public const string PLACE = 'place-order';
    public const string EDIT = 'edit';
    public const string SHOW = 'show';
    public const string REMOVE = 'remove';

    public function __construct(private readonly OrderSettings $orderSettings, private readonly ClockInterface $clock, private readonly OrderRepositoryInterface $orderRepository) {

    }

    #[Override]
    protected function supports(string $attribute, mixed $subject): bool {
        return ($attribute === self::PLACE && $subject instanceof Student)
            || $attribute === self::ANY
            || (in_array($attribute, [ self::EDIT, self::SHOW, self::REMOVE]) && $subject instanceof Order);
    }

    #[Override]
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool {
        switch($attribute) {
            case self::ANY:
                return $this->canViewOrders($token);

            case self::PLACE:
                return $this->canPlace($subject, $token);

            case self::SHOW:
                return $this->canView($subject, $token);

            case self::EDIT:
                return $this->canEdit($subject, $token);

            case self::REMOVE:
                return $this->canRemove($subject, $token);
        }

        throw new LogicException('This code should not be reached!');
    }

    private function canViewOrders(TokenInterface $token): bool {
        $user = $token->getUser();

        if(!$user instanceof User) {
            return false;
        }

        return $user->getAssociatedStudents()->count() > 0;
    }

    private function canPlace(Student $student, TokenInterface $token): bool {
        if(!$this->canViewOrders($token)) {
            return false;
        }

        $user = $token->getUser();

        if(!$user instanceof User) {
            return false;
        }

        if($this->orderSettings->windowStart === null || $this->orderSettings->windowEnd === null) {
            return false;
        }

        $now = $this->clock->now();
        if(!($this->orderSettings->windowStart <= $now && $now <= $this->orderSettings->windowEnd)) {
            return false;
        }

        foreach($user->getAssociatedStudents() as $associatedStudent) {
            if($associatedStudent->getId() === $student->getId() && $this->orderRepository->findForStudentInRange($associatedStudent, $this->orderSettings->windowStart, $this->orderSettings->windowEnd) !== null) {
                return false;
            }
        }

        return true;
    }

    private function canView(Order $order, TokenInterface $token): bool {
        $user = $token->getUser();

        if(!$user instanceof User) {
            return false;
        }

        foreach($user->getAssociatedStudents() as $student) {
            if($order->getStudent()->getId() === $student->getId()) {
                return true;
            }
        }

        return false;
    }

    private function canEdit(Order $order, TokenInterface $token): bool {
        if(!$this->canView($order, $token)) {
            return false;
        }

        if($this->orderSettings->windowStart === null || $this->orderSettings->windowEnd === null) {
            return false;
        }

        $now = $this->clock->now();
        return $this->orderSettings->windowStart <= $now && $now <= $this->orderSettings->windowEnd;
    }

    private function canRemove(Order $order, TokenInterface $token): bool {
        return $this->canEdit($order, $token);
    }
}
