<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VehicleFullResource extends JsonResource {

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
            "rent_cost" => $this->rent_cost,
            "daily_distance_limit" => $this->daily_distance_limit,
            "cost_per_kilometer" => $this->cost_per_kilometer,
            "image" => $this->extra->image,
            "transmission" => $this->transmission,
            "type" => $this->type,
            "door_count" => $this->door_count,
            "seat_count" => $this->seat_count,
            "heated_seats" => $this->heated_seats,
            "ac" => $this->ac,
            "aux" => $this->aux,
            "colour" => $this->colour,
            "drivetrain" => $this->drivetrain,
            "horsepower" => $this->horsepower,
            "fuel_capacity" => $this->fuel_capacity,
            "fuel_type" => $this->fuel_type,
            "tyres" => $this->tyres,
            "avg_consumption" => $this->avg_consumption,
            "wheelchair" => $this->wheelchair,
            "child_seat" => $this->child_seat,
            "backup_camera" => $this->backup_camera,
            "parking_sensors" => $this->parking_sensors
        ];
    }
}
