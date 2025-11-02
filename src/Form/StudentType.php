<?php

namespace App\Form;

use App\Entity\Stop;
use App\Entity\Student;
use App\Settings\ProfileSettings;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

class StudentType extends AbstractType {

    public function __construct(
        private readonly ProfileSettings $profileSettings
    ) {

    }

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'label.firstname',
                'disabled' => true
            ])
            ->add('lastname', TextType::class, [
                'label' => 'label.lastname',
                'disabled' => true
            ])
            ->add('email', EmailType::class, [
                'label' => 'label.email',
                'disabled' => true
            ])
            ->add('birthday', DateType::class, [
                'label' => 'label.birthday',
                'disabled' => true
            ])
            ->add('street', TextType::class, [
                'label' => 'label.street',
                'disabled' => true
            ])
            ->add('houseNumber', TextType::class, [
                'label' => 'label.house_number',
                'disabled' => true
            ])
            ->add('plz', TextType::class, [
                'label' => 'label.plz',
                'disabled' => true
            ])
            ->add('city', TextType::class, [
                'label' => 'label.city',
                'disabled' => true
            ])
            ->add('stop', EntityType::class, [
                'label' => 'label.stop.label',
                'placeholder' => 'label.stop.placeholder',
                'class' => Stop::class,
                'attr' => [
                    'data-choice' => 'true'
                ]
            ])
            ->add('publicSchool', SchoolType::class, [
                'label' => 'label.public_school.label',
                'help' => 'label.public_school.help',
                'placeholder' => 'label.public_school.placeholder',
                'attr' => [
                    'data-choice' => 'true'
                ]
            ])
            ->add('distanceToPublicSchool', DistanceType::class, [
                'label' => 'label.distance_to_public_school.label',
                'help' => 'label.distance_to_public_school.help'
            ])
            ->add('distanceToSchool', DistanceType::class, [
                'label' => 'label.distance_to_school.label',
                'help' => 'label.distance_to_school.help'
            ])
            ->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
                $form = $event->getForm();
                /** @var Student $data */
                $data = $event->getData();

                if($data->getConfirmedDistanceToSchool() > 0) {
                    if($this->profileSettings->lockStopIfDistanceIsConfirmed) {
                        $this->disableField($form, $form->get('stop'));
                    }

                    if($this->profileSettings->lockDistanceToSchoolIfConfirmed) {
                        $this->disableField($form, $form->get('distanceToSchool'));
                    }
                }

                if($data->getConfirmedDistanceToPublicSchool() > 0) {
                    if($this->profileSettings->lockPublicSchoolIfDistanceIsConfirmed) {
                        $this->disableField($form, $form->get('publicSchool'));
                    }

                    if($this->profileSettings->lockDistanceToPublicSchoolIfConfirmed) {
                        $this->disableField($form, $form->get('distanceToPublicSchool'));
                    }
                }
            });
    }

    private function disableField(FormInterface $form, FormInterface $field): void {
        $form->remove($field->getName());

        dump($field);

        $form->add(
            $field->getName(),
            get_class($field->getConfig()->getType()->getInnerType()),
            array_merge(
                $field->getConfig()->getOptions(),
                [
                    'disabled' => true
                ]
            )
        );
    }
}
