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
        'productName'=>$this->ProductName,
        'productDescription'=>$this->ProductDescription,
        'productWeight'=>$this->ProductWeight,
        'productImages' => $this->ProductFile ? asset('storage/' . $this->ProductImages) : null,
        'status'=>$this->Status,
        'submissionDate'=>$this->SubmissionDate
        ];
    }
}
