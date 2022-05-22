<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Services\VehicleFeatureService;
use Carbon\Carbon;
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
            ->first();

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

    // Function that updates vehicle and vehicle features in database
    public function update(Request $request, $id) {
        try {
            $validatedFields = $request->validate([
                'description' => 'nullable',
                'licence_plate' => 'nullable',
                'registered_until' => 'nullable',
                'rent_cost' => 'nullable',
                'daily_distance_limit' => 'nullable',
                'cost_per_kilometer' => 'nullable',
                'rating_avg' => 'nullable'
            ]);

            DB::beginTransaction();
            $vehicle = Vehicle::findOrFail($id)->update($validatedFields);
            $this->vehicleFeatureService->update($request, $id);
            DB::commit();

            $vehicle = DB::table('vehicles')
                ->join('vehicle_features', 'vehicles.vehicle_id', '=', 'vehicle_features.vehicle_id')
                ->where('vehicles.vehicle_id', $id)
                ->first();

            return response()->json($vehicle, 200);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 400);
        }
    }


    // Function searches vehicles based on availability, location and features
    public function search(Request $request) {

        $validatedFields = $request->validate([
            'latitude' => 'required|numeric|min:-90|max:90',
            'longitude' => 'required|numeric|min:-180|max:180',
            'radius' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date'
        ]);

        $latpoint = $validatedFields['latitude'];
        $longpoint = $validatedFields['longitude'];
        $radius = $validatedFields['radius'];
        $start_date = Carbon::parse($validatedFields['start_date'])->startOfDay();
        $end_date = Carbon::parse($validatedFields['end_date'])->endOfDay();

        // Query taken from http://www.plumislandmedia.net/mysql/haversine-mysql-nearest-loc/
        $vehicles = DB::select(DB::raw("
                    SELECT *
                    FROM (
                        SELECT v.*, vf.*,
                               a.start_time, a.end_time,
                               a.latitude, a.longitude,
                               p.radius,
                               p.distance_unit
                                    * DEGREES(ACOS(LEAST(1.0, COS(RADIANS(p.latpoint))
                                    * COS(RADIANS(a.latitude))
                                    * COS(RADIANS(p.longpoint - a.longitude))
                                    + SIN(RADIANS(p.latpoint))
                                    * SIN(RADIANS(a.latitude))))) AS distance
                        FROM availabilities AS a
                            JOIN vehicles v on v.vehicle_id = a.vehicle_id
                            JOIN vehicle_features vf on a.vehicle_id = vf.vehicle_id
                        JOIN (   /* parametri upita */
                            SELECT $latpoint  AS latpoint,  $longpoint AS longpoint,
                                   $radius AS radius,      111.045 AS distance_unit
                            ) AS p ON 1=1
                        WHERE a.latitude
                            BETWEEN p.latpoint  - (p.radius / p.distance_unit)
                            AND p.latpoint  + (p.radius / p.distance_unit)
                          AND a.longitude
                              BETWEEN p.longpoint - (p.radius / (p.distance_unit * COS(RADIANS(p.latpoint))))
                              AND p.longpoint + (p.radius / (p.distance_unit * COS(RADIANS(p.latpoint))))
                        ) AS d
                        WHERE distance <= radius
                        AND d.start_time <= '$start_date'
                        AND d.end_time >= '$end_date'
                        ORDER BY distance
                     "));
        return response()->json($vehicles, 200);
    }
}
