<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
                "pname" => $this->pname,
                "description"=> $this->description,
                "price"=> $this->price,
                "quantity"=> $this->quantity,
                "userID"=> $this->userID,
                "photoURL"=> $this->photoURL,
                "category"=> [
                    "id" => $this->cate_id,
                    "name" => $this->category->name,
                ],
                "owner" => [
                    "id" => $this->user->id,
                    "name" => $this->user->name,
                ],
                "created_at"=> $this->created_at,
                "updated_at"=> $this->updated_at,
            ];
    }
}