<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource {

    //wrap the internal object with "user" key
    public static $wrap = 'user';

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request) {

        return [
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,
            'surname' => $this->surname,
            'date_of_birth' => $this->date_of_birth ? Carbon::parse($this->date_of_birth)->toDateString() : null,
            'license_id' => $this->license_id,
            'renter_avg_rating' => $this->renter_avg_rating,
            'rentee_avg_rating' => $this->rentee_avg_rating,
        ];

    }
}
