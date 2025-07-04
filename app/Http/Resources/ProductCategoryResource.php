<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->CategoryID,    
            'name'=>$this->Name,
            'description'=>$this->Description,
            'smithing'=>$this->smithing,
            'categoryFile' => $this->CategoryFile ? asset('storage/' . $this->CategoryFile) : null,
        ];
    }
}
