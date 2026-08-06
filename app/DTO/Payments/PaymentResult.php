<?php

namespace App\DTO\Payments;

class PaymentResult
{
  public function __construct(
    public bool $isRedirect = false,
    public ?string $redirectUrl = null,
    public ?string $reference = null,
    public array $meta = [],
    public ?string $message = null,
  ) {}
}
