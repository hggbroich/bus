<?php

namespace App\Controller\Order;

use App\Entity\Order;
use App\Entity\School;
use App\Entity\Student;
use App\Entity\StudentSibling;
use App\Entity\User;
use App\Form\OrderType;
use App\Repository\OrderRepositoryInterface;
use App\Security\Voter\OrderVoter;
use App\Settings\OrderSettings;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class PlaceOrderAction extends AbstractController {

    public function __construct(private readonly OrderRepositoryInterface $orderRepository, private readonly OrderSettings $orderSettings) { }


    #[Route('/orders/place/{uuid}', name: 'place_order')]
    public function __invoke(
        #[MapEntity(mapping: ['uuid' => 'uuid'])] Student $student,
        Request $request,
        #[CurrentUser] User $user
    ): Response {
        $this->denyAccessUnlessGranted(OrderVoter::PLACE, $student);

        $order = new Order();
        $order->setStudent($student);
        $this->addSiblings($order, $user, $this->orderSettings->school);
        $orderDataWasTakenFrom = $this->addRecentOrSiblingData($order, $user);

        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->orderRepository->persist($order);
            $this->addFlash('success', 'orders.place.success');

            return $this->redirectToRoute('orders');
        }

        return $this->render('orders/place.html.twig', [
            'form' => $form->createView(),
            'student' => $student,
            'orderDataWasTakenFrom' => $orderDataWasTakenFrom
        ]);
    }

    private function addRecentOrSiblingData(Order $order, User $user): Order|null {
        if($this->orderSettings->windowStart === null || $this->orderSettings->windowEnd === null) {
            return null;
        }

        // Find sibling
        foreach($user->getAssociatedStudents() as $student) {
            $recent = $this->orderRepository->findForStudentInRange($student, $this->orderSettings->windowStart, $this->orderSettings->windowEnd);

            if($recent === null || $recent->getCreatedBy() !== $user->getUserIdentifier()) { // do not leak information
                continue;
            }

            $this->applyFromOtherOrder($order, $recent);
            return $recent;
        }

        // Find most recent order for student
        $recent = $this->orderRepository->findMostRecentForStudent($order->getStudent());

        if($recent === null || $recent->getCreatedBy() !== $user->getUserIdentifier()) { // do not leak information
            return null;
        }

        $this->applyFromOtherOrder($order, $recent);
        return $recent;
    }

    private function applyFromOtherOrder(Order $order, Order $recent): void {
        $order->setCountry($recent->getCountry());
        $order->setPhoneNumber($recent->getPhoneNumber());
        $order->setEmail($recent->getEmail());
        $order->setIban($recent->getIban());
        $order->setBic($recent->getBic());
    }

    private function addSiblings(Order $order, User $user, School|null $school): void {
        foreach($user->getAssociatedStudents() as $student) {
            if($order->getStudent()->getId() === $student->getId()) {
                continue;
            }

            if($student->getStreet() !== $order->getStudent()->getStreet()
                || $student->getHouseNumber() !== $order->getStudent()->getHouseNumber()
                || $student->getPlz() !== $order->getStudent()->getPlz()
                || $student->getCity() !== $order->getStudent()->getCity()) {
                continue;
            }

            $order->addSibling(
                new StudentSibling()
                    ->setStudentAtSchool($student)
                    ->setFirstname($student->getFirstname())
                    ->setLastname($student->getLastname())
                    ->setSchool($school)
            );
        }

    }
}
