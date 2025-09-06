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
            'id'=>$this->ProductID,
            'name'=>$this->Name,
            'decription'=>$this->Description,
            'karat'=>$this->karat,
            'price'=>$this->Price,
            'quantity'=>$this->quantity,
            'productFile' => $this->ProductFile ? asset('storage/' . $this->ProductFile) : null,
            'category_name' => $this->Category ? $this->Category->Name : null,
            'smithing' => $this->Category ? $this->Category->smithing : null,
            'isFeatured'=>$this->IsFeatured,
            'categoryid'=>$this->CategoryID,
        ];
    }
}
