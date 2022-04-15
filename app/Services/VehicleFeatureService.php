<?php

namespace App\Services;

use App\Models\VehicleFeatures;
use Illuminate\Http\Request;

class VehicleFeatureService {

    // Function that stores vehicle's features into the database
    public function store($featureArray) {
        VehicleFeatures::create($featureArray);
    }

    // Function that updates vehicle's features in the database
    public function update(Request $request, $id) {
        $validatedFields = $request->validate([
            'heated_seats' => 'nullable',
            'ac' => 'nullable',
            'aux' => 'nullable',
            'colour' => 'nullable',
            'fuel_type' => 'nullable',
            'tyres' => 'nullable',
            'avg_consumption' => 'nullable',
            'wheelchair' => 'nullable',
            'child_seat' => 'nullable',
            'backup_camera' => 'nullable',
            'parking_sensors'
        ]);

        VehicleFeatures::findOrFail($id)->update($validatedFields);
    }
}
