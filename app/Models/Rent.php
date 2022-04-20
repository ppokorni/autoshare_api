<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class Rent
 *
 * @property int $id
 * @property int $user_id
 * @property int $availabity_id
 * @property string $start_time
 * @property string $end_time
 * @property boolean $confirmed
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 *
 */
class Rent extends Pivot {

    protected $table = 'rents';

    protected $fillable = [
        'user_id',
        'availabity_id',
        'start_time',
        'end_time',
        'confirmed',
    ];

    protected $dates = [
        'start_time',
        'end_time',
    ];

    // Both of these are valid relation names
    public function rentee() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function availability() {
        return $this->belongsTo('App\Models\Availability');
    }

    //has one through relationship for easy access to the availabilityVehicle
    public function availabilityVehicle() {
        return $this->hasOneThrough('App\Models\Vehicle', 'App\Models\Availability', 'id', 'vehicle_id', 'id', 'vehicle_id');
    }


}
