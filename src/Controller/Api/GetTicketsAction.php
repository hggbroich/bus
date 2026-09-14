<?php

namespace App\Controller\Api;

use App\Repository\TicketRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetTicketsAction extends AbstractController {

    #[Route('/api/tickets')]
    public function __invoke(
        TicketRepositoryInterface $ticketRepository
    ): JsonResponse {
        $tickets = $ticketRepository->findAll();
        return $this->json($tickets);
    }
}
