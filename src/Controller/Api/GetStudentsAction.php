<?php

namespace App\Controller\Api;

use App\Repository\StudentRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetStudentsAction extends AbstractController {

    #[Route('/api/students')]
    public function __invoke(
        StudentRepositoryInterface $studentRepository
    ): JsonResponse {
        $students = $studentRepository->findAll();
        return $this->json($students);
    }
}
