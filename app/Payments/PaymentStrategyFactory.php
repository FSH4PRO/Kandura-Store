<?php

namespace App\Payments;

use App\Enums\PaymentMethod;
use App\Payments\Contracts\PaymentStrategy;
use App\Payments\Strategies\CodPaymentStrategy;
use App\Payments\Strategies\StripePaymentStrategy;
use App\Payments\Strategies\WalletPaymentStrategy;

class PaymentStrategyFactory
{
    public function make(PaymentMethod $method): PaymentStrategy
    {
        return match ($method) {
            PaymentMethod::COD    => app(CodPaymentStrategy::class),
            PaymentMethod::Wallet => app(WalletPaymentStrategy::class),
            PaymentMethod::Stripe => app(StripePaymentStrategy::class),
        };
    }
}
