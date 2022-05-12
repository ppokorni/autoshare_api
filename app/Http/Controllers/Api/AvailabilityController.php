<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AvailabilityResource;
use App\Models\Availability;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class AvailabilityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response | \Illuminate\Http\JsonResponse
     */
    public function index(Request $request) {
        try {
            $request->has('vehicle_ids') ? $vehicle_ids = explode(',', $request->vehicle_ids) : $vehicle_ids = [];
            //dd(sizeof($vehicle_ids), $vehicle_ids);
            $availabilities = Availability::when(sizeof($vehicle_ids), function($query) use ($vehicle_ids) {
                return $query->whereIn('vehicle_id', $vehicle_ids);
            })->get();

            return AvailabilityResource::collection($availabilities)->response();
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store new availability for a vehicle.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response | \Illuminate\Http\JsonResponse
     */
    public function store(Request $request){

        try {
            $data = $request->validate(
                [
                    'vehicle_id' => 'required|exists:vehicles,vehicle_id',
                    'start_date' => 'required|date',
                    'end_date' => 'required|date',
                    'latitude' => 'required|numeric|min:-90|max:90',
                    'longitude' => 'required|numeric|min:-180|max:180',
                ],
                $request->all()
            );

            $vehicle = Vehicle::where('vehicle_id', $data['vehicle_id'])->firstOrFail();

            $start_time = Carbon::parse($data['start_date'])->startOfDay();
            $end_time = Carbon::parse($data['end_date'])->endOfDay();

            // Check if there are any availabilities overlapping with the new availability
            if ($this->countOverlappingAvailabilities($vehicle, $start_time, $end_time) > 0) {
                throw new \Exception('There are overlapping availabilities for this vehicle');
            }

            $availability = $vehicle->availabilities()->create([
                'start_time' => $start_time,
                'end_time' => $end_time,
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
            ]);

            return (new AvailabilityResource($availability))->response();

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        } catch (ValidationException $e) {
            return response()->json(['error' => implode(', ', Arr::collapse($e->errors()))], 400);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response | \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $availability = Availability::where('id', $id)->firstOrFail();

            return (new AvailabilityResource($availability))->response();
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response | \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $availability = Availability::where('id', $id)->firstOrFail();

            $data = $request->validate(
                [
                    'start_date' => 'required|date',
                    'end_date' => 'required|date',
                    'latitude' => 'required|numeric|min:-90|max:90',
                    'longitude' => 'required|numeric|min:-180|max:180',
                ],
                $request->all()
            );

            $vehicle = $availability->vehicle;

            $start_time = Carbon::parse($data['start_date'])->startOfDay();
            $end_time = Carbon::parse($data['end_date'])->endOfDay();

            // Check if there are any availabilities overlapping with the availability that's being updated, excluding self
            if ($this->countOverlappingAvailabilities($vehicle, $start_time, $end_time, $availability->id) > 0) {
                throw new \Exception('There are overlapping availabilities for this vehicle');
            }

            $availability->update([
                'start_time' => $start_time,
                'end_time' => $end_time,
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
            ]);

            return (new AvailabilityResource($availability))->response();
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        } catch (ValidationException $e) {
            return response()->json(['error' => implode(', ', $e->errors())], 400);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response | \Illuminate\Http\JsonResponse
     */
    public function destroy($id) {
        try {
            $availability = Availability::where('id', $id)->firstOrFail();

            //TODO: Check if there are any reservations for this availability

            $availability->delete();

            return response()->json(['success' => 'Availability deleted successfully']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Count the number of availabilities that overlap with the given time range for the given vehicle
     * @param Vehicle $vehicle
     * @param Carbon $start_time
     * @param Carbon $end_time
     * @param int $exclude_id
     *
     * @return int
     */
    public function countOverlappingAvailabilities($vehicle, $start_time, $end_time, $exclude_id = null) {
        return $vehicle->availabilities()
            ->where('start_time', '<=', $end_time)
            ->where('end_time', '>=', $start_time)
            // Exclude the current availability
            ->when($exclude_id, function ($query) use ($exclude_id) {
                return $query->where('id', '!=', $exclude_id);
            })
            ->count();
    }
}

