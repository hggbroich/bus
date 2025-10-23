<?php

namespace App\Doctrine\Encryption\Subscriber;

use App\Doctrine\Encryption\Attribute\Encrypt;
use App\Doctrine\Encryption\Encryptors\EncryptorInterface;
use App\Doctrine\Encryption\Preview\PreviewGenerator;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostLoadEventArgs;
use Doctrine\ORM\Events;
use ReflectionClass;
use Symfony\Component\PropertyAccess\PropertyAccess;

#[AsDoctrineListener(event: Events::onFlush)]
#[AsDoctrineListener(event: Events::postLoad)]
readonly class EncryptSubscriber {

    public function __construct(
        private PreviewGenerator $previewGenerator,
        private EncryptorInterface $encryptor
    ) {

    }

    public function postLoad(PostLoadEventArgs $eventArgs): void {
        $em = $eventArgs->getObjectManager();
        $entity = $eventArgs->getObject();

        $reflection = new ReflectionClass($entity);
        $accessor = PropertyAccess::createPropertyAccessor();

        foreach($reflection->getProperties() as $property) {
            $attribute = $property->getAttributes(Encrypt::class)[0] ?? null;
            $attribute = $attribute?->newInstance();

            if (!$attribute instanceof Encrypt) {
                continue;
            }

            if($attribute->preventEncryptionValuePropertyName === null) {
                continue;
            }

            $accessor->setValue($entity, $attribute->preventEncryptionValuePropertyName, $accessor->getValue($entity, $property->getName()));
        }
    }

    public function onFlush(OnFlushEventArgs $eventArgs): void {
        $em = $eventArgs->getObjectManager();
        $uow = $em->getUnitOfWork();

        foreach([...$uow->getScheduledEntityInsertions(), ...$uow->getScheduledEntityUpdates()] as $entity) {
            $originalEntity = $uow->getOriginalEntityData($entity);
            $success = $this->encryptIfNecessary($entity, $originalEntity);

            if($success) {
                $classMetadata = $em->getClassMetadata(get_class($entity));
                $uow->recomputeSingleEntityChangeSet($classMetadata, $entity);
            }
        }
    }

    private function encryptIfNecessary(object $entity, array $originalEntity): bool {
        $reflection = new ReflectionClass($entity);
        $accessor = PropertyAccess::createPropertyAccessor();

        $hasEncrypted = false;

        foreach($reflection->getProperties() as $property) {
            $attribute = $property->getAttributes(Encrypt::class)[0] ?? null;
            $attribute = $attribute?->newInstance();

            if (!$attribute instanceof Encrypt) {
                continue;
            }

            $value = $accessor->getValue($entity, $property->getName());
            $originalValue = null;

            if ($accessor->isReadable($originalEntity, $property->getName())) {
                $originalValue = $accessor->getValue($originalEntity, $property->getName());
            }

            if ($value === $originalValue) { // Value has not changed -> do not encrypt again
                continue; // Prevent encrypting preview value
            }

            if($attribute->preventEncryptionValuePropertyName !== null) {
                $preventValue = $accessor->getValue($entity, $attribute->preventEncryptionValuePropertyName);

                if($preventValue === $value) { // The value was explicitly set to this value, to not encrypt again
                    continue;
                }
            }

            $previewValue = $this->previewGenerator->generatePreview($attribute, $value);
            $encryptedValue = $this->encryptor->encrypt($value);

            $accessor->setValue($entity, $property->getName(), $previewValue);
            $accessor->setValue($entity, $attribute->encryptedPropertyName, $encryptedValue);

            $hasEncrypted = true;
        }

        return $hasEncrypted;
    }
}
