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
            'category_ID'=>$this->CategoryID,    
            'name'=>$this->Name,
            'description'=>$this->Description,
            'categoryFile'=>$this->CategoryFile
        ];
    }
}
