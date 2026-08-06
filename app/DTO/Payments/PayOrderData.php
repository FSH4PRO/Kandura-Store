<?php

namespace App\DTO\Payments;

use App\Enums\PaymentMethod;

class PayOrderData
{
    public function __construct(
        public PaymentMethod $method,
        public ?string $successUrl = null,
        public ?string $cancelUrl = null,
    ) {}
}