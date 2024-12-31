<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->image ? asset("images/{$this->image}") : null,
            'status' => $this->status,
            'is_featured' => $this->is_featured,
            'category_id' => $this->category_id,
            'prices' => PriceResource::collection($this->prices),
            'category' => new CategoryResource($this->whenLoaded('category')),
        ];
    }
}
