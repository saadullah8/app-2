<?php

namespace App\Http\Resources\Api\Card;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class CardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'lastDigits' => $this->lastDigits,
            'cardIcon' => $this->brandImageURL!=null?url('img/credit'.str_replace(' ','-',strtolower($this->brandImageURL).'.png')):url('Unknown.jpeg'),
            'isDefault' => filter_var($this->isDefault, FILTER_VALIDATE_BOOLEAN),
        ];
    }
}
