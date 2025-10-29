<?php

namespace App\Repository;

use App\Entity\Order;
use App\Entity\Student;
use DateTime;

interface OrderRepositoryInterface {

    /**
     * @param Student[] $students
     * @param PaginationQuery $paginationQuery
     * @return PaginatedResult<Order>
     */
    public function findAllForStudentsPaginated(array $students, PaginationQuery $paginationQuery): PaginatedResult;

    /**
     * @param Student $student
     * @param DateTime $start
     * @param DateTime $end
     * @return Order|null
     */
    public function findForStudentInRange(Student $student, DateTime $start, DateTime $end): ?Order;

    public function findMostRecentForStudent(Student $student): ?Order;

    /**
     * @param DateTime $start
     * @param DateTime $end
     * @return Order[]
     */
    public function findAllRange(DateTime $start, DateTime $end): array;

    public function persist(Order $order): void;

    public function remove(Order $order): void;
}
