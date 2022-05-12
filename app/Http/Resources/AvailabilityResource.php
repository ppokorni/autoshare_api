<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AvailabilityResource extends JsonResource {

    //wrap the internal object with "user" key
    public static $wrap = 'availability';

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request) {
        return [
            "id" => $this->id,
            "vehicle_id" => $this->vehicle_id,
            "start_time" => $this->start_time,
            "end_time" => $this->end_time,
            "latitude" => $this->latitude,
            "longitude" => $this->longitude
        ];
    }
}
