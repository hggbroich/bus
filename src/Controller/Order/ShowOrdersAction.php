<?php

namespace App\Controller\Order;

use App\Entity\User;
use App\Order\Check\OrderChecker;
use App\Profile\ProfileCompleteChecker;
use App\Repository\OrderRepositoryInterface;
use App\Repository\PaginationQuery;
use App\Settings\OrderSettings;
use App\UI\Filter\StudentFilter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ShowOrdersAction extends AbstractController {

    #[Route('/orders', name: 'orders')]
    public function __invoke(
        #[CurrentUser] User $user,
        OrderRepositoryInterface $orderRepository,
        StudentFilter $studentFilter,
        Request $request,
        OrderSettings $orderSettings,
        ProfileCompleteChecker $profileCompleteChecker,
        OrderChecker $orderChecker,
        #[MapQueryParameter] int $page = 1,
    ): Response {
        $studentFilterView = $studentFilter->handle($request, $user);

        $hasProfileThatIsNotCompletedByParents = false;

        foreach($user->getAssociatedStudents() as $student) {
            if($profileCompleteChecker->isProfileCompletedByParents($student) !== true) {
                $hasProfileThatIsNotCompletedByParents = true;
            }
        }

        $orders = $orderRepository->findAllForStudentsPaginated($studentFilterView->getCurrentOrDefault(), new PaginationQuery($page));
        $violations = [ ];

        foreach($orders->getIterator() as $order) {
            $violations[$order->getId()] = $orderChecker->check($order);
        }

        return $this->render('orders/index.html.twig', [
            'studentFilter' => $studentFilterView,
            'settings' => $orderSettings,
            'hasProfileThatIsNotCompletedByParents' => $hasProfileThatIsNotCompletedByParents,
            'orders' => $orders,
            'violations' => $violations
        ]);
    }
}
