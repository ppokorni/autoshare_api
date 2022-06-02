<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'vehicle_id',
        'start_time',
        'end_time',
        'latitude',
        'longitude'
    ];

    protected $dates = [
        'start_time',
        'end_time',
    ];

    //vehicle relationship
    public function vehicle() {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    //users many-to-many relationship
    public function users() {
        return $this->belongsToMany(User::class, 'rents', 'availability_id', 'user_id');
    }
}
