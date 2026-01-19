<?php

namespace App\Repository;

use App\Entity\Student;
use Override;

class StudentRepository extends AbstractTransactionalRepository implements StudentRepositoryInterface {

    #[Override]
    public function findOneByUuid(string $uuid): ?Student {
        return $this->em->getRepository(Student::class)->findOneBy(['uuid' => $uuid]);
    }

    #[Override]
    public function findAll(): array {
        return $this->em->getRepository(Student::class)->findAll();
    }

    #[Override]
    public function persist(Student $student): void {
        $this->em->persist($student);
        $this->flushIfNotInTransaction();
    }

    #[Override]
    public function remove(Student $student): void {
        $this->em->remove($student);
        $this->flushIfNotInTransaction();
    }

    #[Override]
    public function findAllByEmailOrId(array $emailsOrIds): array {
        $qb = $this->em->createQueryBuilder()
            ->select('s')
            ->from(Student::class, 's');

        $qb
            ->where($qb->expr()->in('s.email', ':emailsOrIds'))
            ->orWhere($qb->expr()->in('s.externalId', ':emailsOrIds'))
            ->setParameter(':emailsOrIds', $emailsOrIds);

        return $qb->getQuery()->getResult();
    }

    #[Override]
    public function findAllCities(): array {
        return $this->em->createQueryBuilder()
            ->select('DISTINCT s.city')
            ->from(Student::class, 's')
            ->getQuery()
            ->getSingleColumnResult();
    }
}
