<?php

namespace App\Controller\Order;

use App\Entity\Order;
use SchulIT\CommonBundle\Http\Attribute\ForbiddenRedirect;
use SchulIT\CommonBundle\Http\Attribute\NotFoundRedirect;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ShowOrderAction extends AbstractController {

    #[NotFoundRedirect(redirectRoute: 'orders', flashMessage: 'orders.not_found')]
    #[ForbiddenRedirect(redirectRoute: 'orders', flashMessage: 'orders.not_found')]
    #[Route('/orders/{uuid}', name: 'show_order')]
    public function __invoke(#[MapEntity(mapping: ['uuid' => 'uuid'])] Order $order): Response {
        return $this->render('orders/show.html.twig', [
            'order' => $order
        ]);
    }
}
