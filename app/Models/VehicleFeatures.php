<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleFeatures extends Model {
    use HasFactory;

    protected $primaryKey = 'vehicle_id';

    protected $fillable = [
        'vehicle_id',
        'transmission',
        'type',
        'door_count',
        'seat_count',
        'heated_seats',
        'ac',
        'aux',
        'colour',
        'drivetrain',
        'horsepower',
        'fuel_capacity',
        'fuel_type',
        'tyres',
        'avg_consumption',
        'wheelchair',
        'child_seat',
        'backup_camera',
        'parking_sensors'
    ];

    // Define one-to-one relation for vehicles
    public function vehicle() {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }
}
