<?php

namespace App\Controller\Order;

use App\Entity\Order;
use App\Repository\OrderRepositoryInterface;
use App\Security\Voter\OrderVoter;
use SchulIT\CommonBundle\Form\ConfirmType;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RemoveOrderAction extends AbstractController {

    #[Route('/orders/{uuid}/remove', name: 'remove_order')]
    public function __invoke(
        #[MapEntity(mapping: ['uuid' => 'uuid'])] Order $order,
        Request $request,
        OrderRepositoryInterface $orderRepository
    ): Response {
        $this->denyAccessUnlessGranted(OrderVoter::REMOVE, $order);

        $form = $this->createForm(ConfirmType::class, null, [
            'message' => 'orders.remove.confirm',
            'message_parameters' => [
                '%firstname%' => $order->getStudent()->getFirstname(),
                '%lastname%' => $order->getStudent()->getLastname(),
                '%grade%' => $order->getStudent()->getGrade()
            ]
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $orderRepository->remove($order);
            $this->addFlash('success', 'orders.remove.success');

            return $this->redirectToRoute('orders');
        }

        return $this->render('orders/remove.html.twig', [
            'form' => $form->createView(),
            'order' => $order
        ]);
    }
}
