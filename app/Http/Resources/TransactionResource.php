<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
      'type' => $this->type,
      'amount' => $this->amount,
      'description' => $this->description,
      'reference_id' => $this->order_id, // Use order_id as reference
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,
      'wallet' => [
        'id' => $this->wallet->id,
        'balance' => $this->wallet->balance,
        'customer' => [
          'id' => $this->wallet->customer->id,
          'user' => [
            'id' => $this->wallet->customer->user->id,
            'name' => $this->wallet->customer->user->name,
            'email' => $this->wallet->customer->user->email,
          ],
        ],
      ],
    ];
  }
}
