<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    use HasFactory;

    protected $guarded = [];

    //vehicle relationship
    public function vehicle() {
        return $this->belongsTo(Vehicle::class);
    }
}