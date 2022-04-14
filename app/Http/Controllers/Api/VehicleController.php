<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Services\VehicleFeatureService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Nette\Schema\ValidationException;

class VehicleController extends Controller {
    protected $vehicleFeatureService;

    // Dependency injection via constructor
    public function __construct(VehicleFeatureService $vehicleFeatureService) {
        $this->vehicleFeatureService = $vehicleFeatureService;
    }

    // Function that lists all vehicles in database
    public function index() {
        $vehicles = Vehicle::all();
        return response()->json($vehicles, 200);
    }

    // Function that gets vehicle with features from id
    public function getById($id) {
        $vehicle = DB::table('vehicles')
            ->join('vehicle_features', 'vehicles.vehicle_id', '=', 'vehicle_features.vehicle_id')
            ->where('vehicles.vehicle_id', $id)
            ->get();

        return response()->json($vehicle, 200);
    }

    // Function that stores vehicle with features into database
    public function store(Request $request) {
        try {
            $validatedFields = $request->validate([
                'owner_id' => 'required',
                'brand' => 'required',
                'model' => 'required',
                'year' => 'required',
                'description' => 'nullable',
                'licence_plate' => 'required',
                'registered_until' => 'required',
                'rent_cost' => 'nullable',
                'daily_distance_limit' => 'nullable',
                'cost_per_kilometer' => 'nullable'
            ]);

            $featureFields = array_diff($request->all(), $validatedFields);
            $vehicleFields = array_merge($validatedFields, ['rating_avg' => 0.0]);

            DB::beginTransaction();
            $vehicle = Vehicle::create($vehicleFields);
            $vehicleFeatures = array_merge(['vehicle_id' => $vehicle->vehicle_id], $featureFields);
            $this->vehicleFeatureService->store($vehicleFeatures);
            DB::commit();

            return response()->json($vehicle, 200);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 400);
        }
    }
}
