<?php

namespace App\Enums;

enum PaymentMethod: string
{
  case COD = 'cod';
  case Stripe = 'stripe';
  case Wallet = 'wallet';
}
