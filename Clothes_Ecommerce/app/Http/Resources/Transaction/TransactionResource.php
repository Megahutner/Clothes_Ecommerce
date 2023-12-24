<?php

namespace App\Http\Resources\Transaction;

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
            'customerId' => $this->customer_id,
            'total' => $this->total,
            'status' => $this->status,
            'payment' => $this->payment,
            'createdDate' => $this->created_at,
            'updatedDate' => $this->updated_at,
        ];
    }
}
