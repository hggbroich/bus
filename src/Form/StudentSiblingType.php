<?php

namespace App\Form;

use App\Entity\Student;
use App\Entity\StudentSibling;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class StudentSiblingType extends AbstractType {

    public function __construct(private readonly TokenStorageInterface $tokenStorage) {

    }

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $user = $this->tokenStorage->getToken()?->getUser();
        $students = [ ];

        if($user instanceof User) {
            $students = $user->getAssociatedStudents()->toArray();
        }

        $builder
            ->add('studentAtSchool', StudentTicketType::class, [
                'choices' => $students,
                'multiple' => false,
                'expanded' => false,
                'attr' => [
                    'data-choice' => 'true'
                ]
            ])
            ->add('school', SchoolType::class, [
                'attr' => [
                    'data-choice' => 'true'
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'label.firstname'
            ])
            ->add('lastname', TextType::class, [
                'label' => 'label.lastname'
            ])
            ->add('birthday', DateType::class, [
                'label' => 'label.birthday'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void {
        parent::configureOptions($resolver);
        $resolver->setDefault('data_class', StudentSibling::class);
    }
}
