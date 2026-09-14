<?php

namespace App\Controller\Api;

use App\Repository\OrderRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class GetOrdersAction extends AbstractController {

    #[Route('/api/orders')]
    public function __invoke(
        OrderRepositoryInterface $orderRepository,
        #[MapQueryString] OrderFilterRequest $filter = new OrderFilterRequest()
    ): JsonResponse {
        $orders = $orderRepository->findAllRange($filter->start, $filter->end);
        $context = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            }
        ];

        return $this->json($orders, context: $context);
    }
}
