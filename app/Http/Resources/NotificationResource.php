<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'event' => $this->data['event'] ?? null,
            // Translation keys + params so the frontend can render this
            // through its own i18n system if the recipient's locale
            // differs from what the backend rendered it in — the
            // 'title'/'body' below are the server-rendered fallback.
            'title_key' => $this->data['title_key'] ?? null,
            'body_key' => $this->data['body_key'] ?? null,
            'params' => $this->data['params'] ?? [],
            'title' => $this->data['title'] ?? null,
            'body' => $this->data['body'] ?? null,
            'action' => $this->data['action'] ?? null,
            'data' => $this->data['data'] ?? [],
            'read_at' => $this->read_at,
            'is_read' => ! is_null($this->read_at),
            'created_at' => $this->created_at,
        ];
    }
}
