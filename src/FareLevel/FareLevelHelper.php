<?php

namespace App\FareLevel;

use App\Entity\FareLevel;
use App\Repository\FareLevelRepositoryInterface;
use App\Repository\StudentRepositoryInterface;

class FareLevelHelper {

    public function __construct(
        private readonly FareLevelRepositoryInterface $fareLevelRepository,
        private readonly StudentRepositoryInterface $studentRepository
    ) {

    }

    public function addMissingLevels(): int {
        $cities = $this->studentRepository->findAllCities();

        $this->fareLevelRepository->beginTransaction();
        $count = 0;

        foreach($cities as $city) {
            $existing = $this->fareLevelRepository->findOneByCity($city);

            if($existing !== null) {
                continue;
            }


            $level = new FareLevel()
                ->setCity($city)
                ->setName($city)
                ->setLevel("0");

            $this->fareLevelRepository->persist($level);
            $count++;
        }

        $this->fareLevelRepository->commit();
        return $count;
    }
}
