<?php

namespace App\FareLevel;

use App\Entity\Order;
use App\Repository\FareLevelRepositoryInterface;
use Exception;

readonly class FareLevelSetter {

    public function __construct(private FareLevelRepositoryInterface $repository) { }

    public function setFareLevel(Order $order): void {
        $city = $order->getCity();
        $fareLevel = $this->repository->findOneByCity($city);

        if($fareLevel === null) {
            throw new Exception(sprintf('Preisstufe für Ort "%s" nicht vorhanden.', $city));
        }

        $order->setFareLevel($fareLevel);
    }
}
