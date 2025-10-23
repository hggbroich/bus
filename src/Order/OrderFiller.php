<?php

namespace App\Order;

use App\Entity\Order;
use App\Entity\Student;

class OrderFiller {
    public function copyProfileToOrder(Order $order, Student $student): Order{
        $order->setFirstname($student->getFirstname());
        $order->setLastname($student->getLastname());
        $order->setBirthday($student->getBirthday());
        $order->setStreet($student->getStreet());
        $order->setHouseNumber($student->getHouseNumber());
        $order->setPlz($student->getPlz());
        $order->setCity($student->getCity());
        $order->setSgb12($student->isSgb1());
        $order->setStop($student->getStop());
        $order->setPublicSchool($student->getPublicSchool());
        $order->setConfirmedDistanceToPublicSchool($student->getConfirmedDistanceToPublicSchool());
        $order->setConfirmedDistanceToSchool($student->getConfirmedDistanceToSchool());

        return $order;
    }

    public function applyFromOtherOrder(Order $order, Order $recent): void {
        $order->setCountry($recent->getCountry());
        $order->setPhoneNumber($recent->getPhoneNumber());
        $order->setEmail($recent->getEmail());
        $order->setIban($recent->getIban());
        $order->setEncryptedIban($recent->getEncryptedIban());
        $order->setPreventEncryptionValue($recent->getIban());
    }
}
