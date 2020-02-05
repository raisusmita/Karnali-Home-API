<?php

namespace App\Http\Resources\RoomCategory;

use Illuminate\Http\Resources\Json\JsonResource;

class RoomCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'room_category' => $this->room_category,
            'no_of_rooms' => $this->number_of_room,
            'inserted_date'=>date('d-m-Y', strtotime($this->created_at))
        ];
    }
}
