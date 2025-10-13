<?php

namespace App\Doctrine;

use App\Entity\Student;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

#[AsDoctrineListener(event: Events::onFlush)]
readonly class StudentUpdatedEventSubscriber {

    private const array ResetMap = [
        'distanceToPublicSchool' => 'confirmedDistanceToPublicSchool',
        'distanceToSchool' => 'confirmedDistanceToSchool',
    ];

    public function __construct(private AuthorizationCheckerInterface $authorizationChecker) { }

    public function onFlush(OnFlushEventArgs $eventArgs): void {
        if($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            return;
        }

        $uow = $eventArgs->getObjectManager()->getUnitOfWork();

        foreach($uow->getScheduledEntityUpdates() as $entity) {
            if ($entity instanceof Student) {
                $changeset = $uow->getEntityChangeSet($entity);
                $propertyAccessor = PropertyAccess::createPropertyAccessor();

                foreach($changeset as $field => $values) {
                    if(!isset(self::ResetMap[$field])) {
                        continue;
                    }

                    $resetField = self::ResetMap[$field];
                    $propertyAccessor->setValue($entity, $resetField, 0);
                }

                $uow->recomputeSingleEntityChangeSet($eventArgs->getObjectManager()->getClassMetadata(get_class($entity)), $entity);
            }
        }
    }
}
