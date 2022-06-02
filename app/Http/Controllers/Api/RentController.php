<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Availability;
use App\Models\Rent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function response;

class RentController extends Controller {

    /**
     * Stores a new rent into the database and updates availability
     *
     * @param Request $request the request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request) {
        try {
            $rent = $request->validate([
                'user_id' => 'required|numeric',
                'availability_id' => 'required|numeric',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'confirmed' => 'nullable'
            ]);

            DB::beginTransaction();

            $start_time = $rent['start_date'] = Carbon::parse($rent['start_date'])->startOfDay();
            $end_time = $rent['end_date'] = Carbon::parse($rent['end_date'])->endOfDay()->micro(0)->milli(0);

            Rent::create($rent);

            $availability = Availability::where('id', $rent['availability_id'])->firstOrFail();

            if($start_time->eq($availability->start_time) && $availability->end_time->eq($end_time)) { // If rent covers whole period, delete availability
                $availability->delete();
            } else if ($start_time->eq($availability->start_time) ) { // If start dates are equal, update availability start date
                $availability->update([
                    'start_time' => $end_time->addDay()
                ]);
            } else if ($end_time->eq($availability->end_time)) { // If end dates are equal, update availability end date
                $availability->update([
                    'end_time' => $start_time->subDay()
                ]);
            } else { // If it's in the middle, split availability into two new periods
                Availability::create([
                    'vehicle_id' => $availability->vehicle_id,
                    'start_time' => $availability->start_time,
                    'end_time' => $start_time->subDay()->endOfDay(),
                    'latitude' => $availability->latitude,
                    'longitude' => $availability->longitude
                ]);
                Availability::create([
                    'vehicle_id' => $availability->vehicle_id,
                    'start_time' => $end_time->addDay()->startOfDay(),
                    'end_time' => $availability->end_time,
                    'latitude' => $availability->latitude,
                    'longitude' => $availability->longitude
                ]);
                $availability->delete();
            }
            DB::commit();

            return response()->json($rent, 200);
        } catch (\Nette\Schema\ValidationException $e) {
            return response()->json(['error' => $e->errors()], 400);
        }
    }

}

