<?php

namespace App\Controller\Profile;

use App\Entity\Student;
use App\Form\StudentType;
use App\Repository\StudentRepositoryInterface;
use App\Security\Voter\ProfileVoter;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UpdateProfileAction extends AbstractController {
    #[Route('/profile/{uuid}', name: 'update_profile')]
    public function __invoke(#[MapEntity(mapping: ['uuid' => 'uuid'])] Student $student, Request $request, StudentRepositoryInterface $studentRepository): Response {
        $this->denyAccessUnlessGranted(ProfileVoter::Update, $student);

        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $studentRepository->persist($student);
            $this->addFlash('success', 'profile.update.success');

            return $this->redirectToRoute('profile');
        }

        return $this->render('profile/update.html.twig', [
            'form' => $form->createView(),
            'student' => $student
        ]);
    }
}
