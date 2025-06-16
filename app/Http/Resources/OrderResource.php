<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->OrderID,
            'userid'=>$this->UserID,
            'orderDate'=>$this->OrderDate,
            'status'=>$this->Status,
            'totalAmount'=>$this->TotalAmount,
            'shippingAddress'=>$this->ShippingAddress,
            'paymentMethod'=>$this->PaymentMethod,
        ];
    }
}
