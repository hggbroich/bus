<?php

namespace App\Repository;

use App\Entity\Order;
use App\Entity\Student;
use DateTime;
use Override;

class OrderRepository extends AbstractRepository implements OrderRepositoryInterface {

    #[Override]
    public function findAllForStudentsPaginated(array $students, PaginationQuery $paginationQuery): PaginatedResult {
        $qb = $this->em->createQueryBuilder()
            ->select(['o', 's'])
            ->from(Order::class, 'o')
            ->join('o.student', 's')
            ->where('s.id IN (:students)')
            ->setParameter('students', $students)
            ->orderBy('o.createdAt', 'DESC');

        return PaginatedResult::fromQueryBuilder($qb, $paginationQuery);
    }

    #[Override]
    public function findForStudentInRange(Student $student, DateTime $start, DateTime $end): ?Order {
        return $this->em->createQueryBuilder()
            ->select(['o', 's'])
            ->from(Order::class, 'o')
            ->join('o.student', 's')
            ->where('s.id = :student')
            ->andWhere('o.createdAt BETWEEN :start AND :end')
            ->setParameter('student', $student)
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    #[Override]
    public function persist(Order $order): void {
        $this->em->persist($order);
        $this->em->flush();
    }

    #[Override]
    public function remove(Order $order): void {
        $this->em->remove($order);
        $this->em->flush();
    }


    #[Override]
    public function findMostRecentForStudent(Student $student): ?Order {
        return $this->em->createQueryBuilder()
            ->select(['o', 's'])
            ->from(Order::class, 'o')
            ->join('o.student', 's')
            ->where('s.id = :student')
            ->setParameter('student', $student)
            ->setMaxResults(1)
            ->orderBy('o.createdAt', 'DESC')
            ->getQuery()
            ->getOneOrNullResult();
    }

    #[Override]
    public function findAllRange(DateTime $start, DateTime $end): array {
        return $this->em->createQueryBuilder()
            ->select(['o', 's', 't', 'l', 'c'])
            ->from(Order::class, 'o')
            ->leftJoin('o.student', 's')
            ->leftJoin('o.ticket', 't')
            ->leftJoin('o.fareLevel', 'l')
            ->leftJoin('o.depositorCountry', 'c')
            ->where('o.createdAt BETWEEN :start AND :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getResult();
    }
}
