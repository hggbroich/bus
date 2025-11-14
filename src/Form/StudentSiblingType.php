<?php

namespace App\Form;

use App\Entity\Student;
use App\Entity\StudentSibling;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraints\IsTrue;

class StudentSiblingType extends AbstractType {

    public function __construct(private readonly TokenStorageInterface $tokenStorage) {

    }

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $user = $this->tokenStorage->getToken()?->getUser();
        $students = [ ];

        if($user instanceof User) {
            $students = $user->getAssociatedStudents()->toArray();

            if($options['exclude_student'] instanceof Student) {
                $students = array_filter(
                    $students,
                    fn(Student $student): bool => $student->getId() !== $options['exclude_student']->getId()
                );
            }
        }

        $builder
            ->add('studentAtSchool', StudentTicketType::class, [
                'choices' => $students,
                'multiple' => false,
                'expanded' => false,
                'placeholder' => 'Kein Schüler der Schule (alternativ Schüler auswählen)',
                'attr' => [
                    'data-choice' => 'true'
                ]
            ])
            ->add('school', SchoolType::class, [
                'placeholder' => 'Schule auswählen',
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
            ])
            ->add('confirm', CheckboxType::class, [
                'label' => 'orders.siblings.confirm',
                'mapped' => false,
                'constraints' => [
                    new IsTrue()
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void {
        parent::configureOptions($resolver);
        $resolver->setDefault('data_class', StudentSibling::class);
        $resolver->setDefault('exclude_student', null);
    }
}
