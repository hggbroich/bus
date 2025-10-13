<?php

namespace App\Form;

use App\Entity\Student;
use App\Entity\User;
use App\Repository\OrderRepositoryInterface;
use App\Settings\OrderSettings;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class StudentTicketType extends EntityType {

    public function __construct(ManagerRegistry $registry,
                                private readonly TokenStorageInterface $tokenStorage,
                                private readonly OrderRepositoryInterface $orderRepository,
                                private readonly OrderSettings $orderSettings) {
        parent::__construct($registry);
    }

    public function configureOptions(OptionsResolver $resolver): void {
        parent::configureOptions($resolver);

        $user = $this->tokenStorage->getToken()?->getUser();

        if(!$user instanceof User) {
            return;
        }

        if($this->orderSettings->windowStart === null || $this->orderSettings->windowEnd === null) {
            return;
        }

        $resolver->setDefault('label', 'label.student');
        $resolver->setDefault('class', Student::class);
        $resolver->setDefault('expanded', true);
        $resolver->setDefault('multiple', false);
        $resolver->setDefault('choice_label', fn(Student $student): string => sprintf('%s, %s', $student->getLastname(), $student->getFirstname()));

        $students = [ ];

        foreach($user->getAssociatedStudents() as $student) {
            if(!in_array($student->getStatus(), $this->orderSettings->requiredStatusForOrder)) {
                continue;
            }

            if($this->orderRepository->findForStudentInRange($student, $this->orderSettings->windowStart, $this->orderSettings->windowEnd) !== null) {
                continue;
            }

            $students[] = $student;
        }

        $resolver->setDefault('choices', $students);
    }
}
