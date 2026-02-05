<?php

namespace App\Enums;

enum OrderStatus: string
{
  case Pending = 'pending';
  case Accepted = 'accepted';
  case Rejected = 'rejected';
  case Canceled = 'canceled';
  case Completed = 'completed';
  case Processing = 'processing';
}
