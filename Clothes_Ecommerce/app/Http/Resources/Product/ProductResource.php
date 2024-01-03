<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'price'=> $this->price,
            'available'=> $this->available,
            'image'=> $this->image,
            'status'=> $this->status,
            'createdDate' => $this->created_at,
            'updatedDate' => $this->updated_at,
        ];
    }
}
