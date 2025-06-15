<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
        'ProductName'=>$this->ProductName,
        'ProductDescription'=>$this->ProductDescription,
        'ProductWeight'=>$this->ProductWeight,
        'ProductImages' => $this->ProductFile ? asset('storage/' . $this->ProductImages) : null,
        'Status'=>$this->Status,
        'SubmissionDate'=>$this->SubmissionDate
        ];
    }
}
