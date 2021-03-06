<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable {
    use HasFactory, Notifiable, HasApiTokens;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    //get formatted attributes
    protected $appends = [
        'renter_avg_rating',
        'rentee_avg_rating',
    ];

    //Set the user's date of birth to start of day.
    public function setDateOfBirthAttribute($value) {
        $this->attributes['date_of_birth'] = Carbon::parse($value)->startOfDay();
    }

    //Format user's renter rating.
    public function getRenterAvgRatingAttribute($value) {
        return number_format($value, 2);
    }

    //Format user's rentee rating.
    public function getRenteeAvgRatingAttribute($value) {
        return number_format($value, 2);
    }

    // Define many-to-one relation for vehicles
    public function vehicles() {
        return $this->hasMany(Vehicle::class, 'owner_id');
    }

    // Define many-to-many relation for availabilities
    public function availabilities() {
        return $this->belongsToMany(Availability::class, 'rents', 'user_id', 'availability_id');
    }

}
