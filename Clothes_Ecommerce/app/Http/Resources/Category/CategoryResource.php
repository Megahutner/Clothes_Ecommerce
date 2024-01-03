<?php

namespace App\Http\Resources\Category;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Product\ProductResource;


class CategoryResource extends JsonResource
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
            'createdDate' => $this->created_at,
            'updatedDate' => $this->updated_at,
            'products'=>ProductResource::collection($this->whenLoaded('products')),
        ];    }
}
