<?php

namespace App\Controller\Order;

use App\Entity\Order;
use App\Form\OrderType;
use App\Order\OrderFiller;
use App\Repository\OrderRepositoryInterface;
use App\Security\Voter\OrderVoter;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EditOrderAction extends AbstractController {

    #[Route('/orders/{uuid}/edit', name: 'edit_order')]
    public function __invoke(
        #[MapEntity(mapping: ['uuid' => 'uuid'])] Order $order,
        Request $request,
        OrderRepositoryInterface $orderRepository,
        OrderFiller $orderFiller
    ): Response {
        $this->denyAccessUnlessGranted(OrderVoter::EDIT, $order);

        // UPDATE PROFILE DATA
        $orderFiller->copyProfileToOrder($order, $order->getStudent());

        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $orderRepository->persist($order);
            $this->addFlash('success', 'orders.edit.success');

            return $this->redirectToRoute('show_order', [
                'uuid' => $order->getUuid()
            ]);
        }

        return $this->render('orders/edit.html.twig', [
            'form' => $form->createView(),
            'order' => $order,
            'student' => $order->getStudent()
        ]);
    }
}
