<?php

namespace App\Services;

use App\Models\VehicleFeatures;

class VehicleFeatureService {

    // Function that stores vehicle's features into the database
    public function store($featureArray) {
        VehicleFeatures::create($featureArray);
    }

}
