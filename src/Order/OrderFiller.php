<?php

namespace App\Order;

use App\Entity\Order;
use App\Entity\Student;
use App\Entity\StudentSibling;
use App\Settings\OrderSettings;

readonly class OrderFiller {

    public function __construct(private OrderSettings $orderSettings) { }

    public function copyProfileToOrder(Order $order, Student $student): Order{
        $order->setFirstname($student->getFirstname());
        $order->setLastname($student->getLastname());
        $order->setBirthday($student->getBirthday());
        $order->setGender($student->getGender());
        $order->setStreet($student->getStreet());
        $order->setHouseNumber($student->getHouseNumber());
        $order->setPlz($student->getPlz());
        $order->setCity($student->getCity());
        $order->setSgb12($student->isSgb12());
        $order->setStop($student->getStop());
        $order->setPublicSchool($student->getPublicSchool());
        $order->setConfirmedDistanceToPublicSchool($student->getConfirmedDistanceToPublicSchool());
        $order->setConfirmedDistanceToSchool($student->getConfirmedDistanceToSchool());

        return $order;
    }

    public function applyFromOtherOrder(Order $order, Order $recent): void {
        $order->setDepositorFirstname($recent->getDepositorFirstname());
        $order->setDepositorLastname($recent->getDepositorLastname());
        $order->setDepositorBirthday($recent->getDepositorBirthday());
        $order->setDepositorStreet($recent->getDepositorStreet());
        $order->setDepositorHouseNumber($recent->getDepositorHouseNumber());
        $order->setDepositorPlz($recent->getDepositorPlz());
        $order->setDepositorCity($recent->getDepositorCity());
        $order->setDepositorCountry($recent->getDepositorCountry());
        $order->setDepositorPhoneNumber($recent->getDepositorPhoneNumber());
        $order->setDepositorEmail($recent->getDepositorEmail());
        $order->setIban($recent->getIban());
        $order->setEncryptedIban($recent->getEncryptedIban());
        $order->setPreventEncryptionValue($recent->getIban());

        // Siblings
        foreach($recent->getSiblings() as $sibling) {
            if($sibling->getStudentAtSchool()?->getId() !== $order->getStudent()->getId()) {
                $order->addSibling(
                    new StudentSibling()
                        ->setFirstname($sibling->getFirstname())
                        ->setLastname($sibling->getLastname())
                        ->setBirthday($sibling->getBirthday())
                        ->setStudentAtSchool($sibling->getStudentAtSchool())
                );
            }
        }
    }

    public function copyConfirmations(Order $order): void {
        $order->setConfirmations($this->orderSettings->confirmations);
    }
}
