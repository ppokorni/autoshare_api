<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends JsonResource {

    public static $wrap = 'vehicle';

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request) {
        return [
            "vehicle_id" => $this->vehicle_id,
            "owner_id" => $this->owner_id,
            "brand" => $this->brand,
            "model" => $this->model,
            "year" => $this->year,
            "licence_plate" => $this->licence_plate,
            "registered_until" => $this->registered_until,
            "rating_avg" => $this->rating_avg,
            "image" => $this->extra->image
        ];
    }
}
