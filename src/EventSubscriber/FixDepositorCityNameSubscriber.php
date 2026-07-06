<?php

namespace App\EventSubscriber;

use App\Entity\Order;
use App\Repository\CityRepositoryInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Override;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class FixDepositorCityNameSubscriber implements EventSubscriberInterface {

    public function __construct(
        private CityRepositoryInterface $cityRepository
    ) {

    }

    #[Override]
    public static function getSubscribedEvents(): array {
        return [
            BeforeEntityPersistedEvent::class => 'onBeforeEntityPersisted',
            BeforeEntityUpdatedEvent::class => 'onBeforeEntityUpdated',
        ];
    }

    private function correctCityNameIfNecessary(Order $order): void {
        if(empty($order->getDepositorPlz())) {
            return;
        }

        $city = $this->cityRepository->findByPlz($order->getDepositorPlz());

        if($city === null) {
            return;
        }

        $order->setDepositorCity($city->getName());
    }

    public function onBeforeEntityPersisted(BeforeEntityPersistedEvent $event): void {
        $entity = $event->getEntityInstance();

        if($entity instanceof Order) {
            $this->correctCityNameIfNecessary($entity);
        }
    }

    public function onBeforeEntityUpdated(BeforeEntityUpdatedEvent $event): void {
        $entity = $event->getEntityInstance();

        if($entity instanceof Order) {
            $this->correctCityNameIfNecessary($entity);
        }
    }
}
