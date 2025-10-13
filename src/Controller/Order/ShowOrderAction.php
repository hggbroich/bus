<?php

namespace App\Controller\Order;

use App\Entity\Order;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ShowOrderAction extends AbstractController {

    #[Route('/orders/{uuid}', name: 'show_order')]
    public function __invoke(#[MapEntity(mapping: ['uuid' => 'uuid'])] Order $order): Response {
        return $this->render('orders/show.html.twig', [
            'order' => $order
        ]);
    }
}
