<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            "userID" => $this->userID,
            "quantity"=> $this->quantity,
            "status"=> $this->status,
            "select"=> $this->select,
            "product"=> [
                'id' => $this->product->id,
                "pname" => $this->product->pname,
                "description"=> $this->product->description,
                "price"=> $this->product->price,
                "quantity"=> $this->product->quantity,
                "photoURL"=> $this->product->photoURL,
            ],
            "created_at"=> $this->created_at,
            "updated_at"=> $this->updated_at,
        ];
    }
}