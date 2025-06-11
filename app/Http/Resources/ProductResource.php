<?php

namespace App\Http\Resources;

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
            'product_id'=>$this->ProductID,
            'name'=>$this->Name,
            'decription'=>$this->Description,
            'weight'=>$this->Weight,
            'price'=>$this->Price,
            'productFile' => $this->ProductFile ? asset('storage/' . $this->ProductFile) : null,
            'isFeatured'=>$this->IsFeatured,
            'category_name' => $this->Category ? $this->Category->Name : null,
        ];
    }
}
