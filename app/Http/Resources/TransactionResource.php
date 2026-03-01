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
            'transaction_number' => $this->transaction_number,
            'user_id' => $this->user_id,
            'user' => new UserResource($this->whenLoaded('user')),
            'items' => TransactionDetailResource::collection($this->whenLoaded('details')),
            'total_price' => (float) $this->total_price,
            'amount_received' => (float) $this->amount_received,
            'change' => (float) $this->change,
            'status' => $this->status,
            'notes' => $this->notes,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
