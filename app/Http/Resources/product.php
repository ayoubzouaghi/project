<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;
use App\Http\Resources\product as ResourceProduct;

class product extends JsonResource
{


    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    protected $user;

    public function toArray($request)
    {
        $this->user = new User();

        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'price'          => $this->price,
            'color'          => $this->color,
            'user'           => new ResourceProduct($this->user->find($this->user_id)),
        ];
    }
}
