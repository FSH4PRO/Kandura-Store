<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    
    $owner = $this->usable; 

    return [
      'id'         => $this->id,
      'name'       => $this->name,

      // جايين من Admin أو Customer
      'email'      => $owner?->email,
      'phone'      => $owner?->phone,
      'type'       => $owner ? class_basename($owner) : null, 

      'is_active'  => $this->is_active,
      'avatar_url' => $this->avatar_url,
      'created_at' => $this->created_at?->toIso8601String(),
      'updated_at' => $this->updated_at?->toIso8601String(),

      
      'access_token' => $this->when(isset($this->access_token), $this->access_token),
      'token_type'   => $this->when(isset($this->token_type), $this->token_type),
    ];
  }
}
