<?php

namespace App\Order\Continuation;

use App\Entity\Order;
use App\Order\OrderFiller;
use App\Repository\OrderRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use App\Security\Voter\OrderVoter;
use App\Settings\OrderSettings;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

#[AsMessageHandler]
readonly class ContinueOrderMessageHandler {

    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private UserRepositoryInterface $userRepository,
        private AccessDecisionManagerInterface $accessDecisionManager,
        private OrderSettings $orderSettings,
        private OrderFiller $orderFiller,
    ) { }

    public function __invoke(ContinueOrderMessage $message): void {
        $oldOrder = $this->orderRepository->findOneById($message->orderId);

        if($oldOrder === null) { // Old order was (somehow) removed
            return;
        }

        if($oldOrder->getStudent() === null) { // fatal error...
            return;
        }

        $existingOrder = $this->orderRepository->findForStudentInRange(
            $oldOrder->getStudent(),
            $this->orderSettings->windowStart,
            $this->orderSettings->windowEnd
        );

        if($existingOrder !== null) { // A new order already exists
            return;
        }

        if($oldOrder->getStudent()->getGrade() !== null && in_array($oldOrder->getStudent()->getGrade(), $message->ignoreGrades)) { // ignore students with specified grade
            return;
        }

        $user = $this->userRepository->findOneByUsername($oldOrder->getCreatedBy());

        if($user === null) { // The user who created the old order is no longer available
            return;
        }

        $token = new UsernamePasswordToken($user, 'secured', $user->getRoles());
        if($this->accessDecisionManager->decide($token, [ OrderVoter::PLACE ], $oldOrder->getStudent()) !== true) { // Check if user is allowed to place an order for the given student
            return;
        }

        $newOrder = new Order();
        $newOrder->setStudent($oldOrder->getStudent());
        $this->orderFiller->copyProfileToOrder($newOrder, $oldOrder->getStudent());
        $this->orderFiller->applyFromOtherOrder($newOrder, $oldOrder);
        $newOrder->setConfirmations($oldOrder->getConfirmations());

        // We now have to check the students...
        $toDelete = [ ];
        foreach($newOrder->getSiblings() as $sibling) {
            if($sibling->getStudentAtSchool() === null) {
                continue;
            }

            if($this->accessDecisionManager->decide($token, [ OrderVoter::PLACE ], $sibling->getStudentAtSchool()) !== true) {
                $toDelete[] = $sibling;
            }
        }

        foreach($toDelete as $item) {
            $newOrder->removeSibling($item);
        }

        $newOrder->setCreatedBy($user->getUserIdentifier());
        $this->orderRepository->persist($newOrder);
    }
}
