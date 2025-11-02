<?php

namespace App\Order\Check;

use App\Entity\Order;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(self::AUTOCONFIGURE_TAG)]
interface CheckInterface {
    public const string AUTOCONFIGURE_TAG = 'app.order.check';

    /**
     * @param Order $order
     * @return Violation[]
     */
    public function check(Order $order): array;
}
