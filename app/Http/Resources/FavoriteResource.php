<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FavoriteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'userid'=>$this->UserID,
            'productid'=>$this->ProductID,
            'facoriteid'=>$this->FavoriteID,
            'date add'=>now(),
        ];
    }
}
