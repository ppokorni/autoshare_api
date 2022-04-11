<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserController extends Controller {

    /**
     * Display the specified resource.
     *
     * @param  $user_id
     * @return \Illuminate\Http\Response | \Illuminate\Http\JsonResponse
     */
    public function show($user_id) {
        try {
            $user = User::findOrFail($user_id);
            return (new UserResource($user))->response();
        } catch (ModelNotFoundException $e) {
           return response()->json(['error' => 'Model not found'], 404);
        } catch (\Throwable $th) {
           return response()->json(['error' => 'There was an error'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response | \Illuminate\Http\JsonResponse
     */
    public function showOwn() {
        try {
            //TODO: return logged in user's profile
            return response()->json(['error' => 'Not implemented'], 501);
        } catch (ModelNotFoundException $e) {
           return response()->json(['error' => 'Model not found'], 404);
        } catch (\Throwable $th) {
           return response()->json(['error' => 'There was an error'], 500);
        }
    }
}
