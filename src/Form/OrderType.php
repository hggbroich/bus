<?php

namespace App\Form;

use App\Entity\Order;
use App\FareLevel\FareLevelSetter;
use App\Settings\OrderSettings;
use LogicException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

class OrderType extends AbstractType {

    public function __construct(
        private readonly OrderSettings $orderSettings,
        private readonly FareLevelSetter $fareLevelSetter
    ) {

    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefault('exclude_student', null);
        $resolver->setDefault('validation_groups', function(FormInterface $form): array {
            $order = $form->getData();

            if(!$order instanceof Order) {
                throw new LogicException(sprintf('Bound object must be of type %s, %s given', Order::class, gettype($order)));
            }

            if($order->getIban() !== $order->getPreventEncryptionValue()) {
                return ['Default', 'iban'];
            }

            return ['Default'];
        });
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('depositorFirstname', TextType::class, [
                'label' => 'label.firstname'
            ])
            ->add('depositorLastname', TextType::class, [
                'label' => 'label.lastname'
            ])
            ->add('depositorBirthday', BirthdayType::class, [
                'label' => 'label.birthday'
            ])
            ->add('depositorStreet', TextType::class, [
                'label' => 'label.street'
            ])
            ->add('depositorHouseNumber', TextType::class, [
                'label' => 'label.house_number'
            ])
            ->add('depositorPlz', IntegerType::class, [
                'label' => 'label.plz'
            ])
            ->add('depositorCity', TextType::class, [
                'label' => 'label.city'
            ])
            ->add('depositorCountry', CountryType::class)
            ->add('depositorPhoneNumber', TextType::class, [
                'label' => 'label.phone',
            ])
            ->add('depositorEmail', EmailType::class, [
                'label' => 'label.email',
            ])
            ->add('iban', TextType::class, [
                'label' => 'label.iban',
            ])
            ->add('siblings', CollectionType::class, [
                'entry_type' => StudentSiblingType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'entry_options' => [
                    'exclude_student' => $options['exclude_student']
                ]
            ]);

        if(count($this->orderSettings->confirmations) > 0) {
            $builder
                ->add('confirm', CheckboxType::class, [
                    'label' => 'label.confirm_confirmations',
                    'mapped' => false,
                    'required' => true,
                    'constraints' => [
                        new IsTrue()
                    ]
                ]);
        }

        // Fare level must be set here so validation does not fail
        $builder
            ->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
                $order = $event->getData();

                if(!$order instanceof Order) {
                    return;
                }

                if(empty($order->getCity())) {
                    return;
                }

                $this->fareLevelSetter->setFareLevel($order);

                foreach($order->getSiblings() as $sibling) {
                    if($sibling->getStudentAtSchool() === null || $this->orderSettings->school === null) {
                        continue;
                    }

                    $sibling->setSchool($this->orderSettings->school);
                }
            });
    }
}
