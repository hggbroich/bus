<?php

namespace App\EventSubscriber;

use App\Entity\ApiAccessToken;
use App\Utils\SecurityUtils;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Override;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class EasyAdminApiAccessTokenSubscriber implements EventSubscriberInterface {

    public function __construct(
        private SecurityUtils $securityUtils
    ) {

    }

    public function onBeforeEntityPersisted(BeforeEntityPersistedEvent $event): void {
        $entity = $event->getEntityInstance();

        if(!$entity instanceof ApiAccessToken) {
            return;
        }

        $entity->setAccessToken($this->securityUtils->createRandomString(64));
    }

    #[Override]
    public static function getSubscribedEvents() {
        return [
            BeforeEntityPersistedEvent::class => 'onBeforeEntityPersisted',
        ];
    }
}
