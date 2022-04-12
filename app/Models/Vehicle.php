<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model {
    use HasFactory;

    protected $primaryKey = 'vehicle_id';

    protected $fillable = [
        'owner_id',
        'brand',
        'model',
        'year',
        'description',
        'licence_plate',
        'registered_until',
        'rent_cost',
        'daily_distance_limit',
        'cost_per_kilometer'
    ];

    // Get formatted attributes
    protected $appends = [
        'rating_avg'
    ];

    // Format vehicle's rating
    public function getRatingAvgAttribute($value) {
        return number_format($value, 2);
    }

    // Define many-to-one relation for users
    public function user() {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // Define one-to-one relation for vehicles
    public function vehicleFeatures() {
        return $this->hasOne(VehicleFeatures::class);
    }
}
